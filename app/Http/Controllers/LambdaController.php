<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\LambdaService;

class LambdaController extends Controller
{
    protected $lambdaService;

    public function __construct(LambdaService $lambdaService)
    {
        $this->lambdaService = $lambdaService;
    }

    public function procesarTexto(Request $request)
    {
        $request->validate([
            'texto' => 'required|string',
            'accion' => 'sometimes|string|in:uppercase,reverse,count,words'
        ]);

        $texto = $request->input('texto');
        $accion = $request->input('accion', 'uppercase');

        $resultado = $this->lambdaService->procesarConLambda($texto, $accion);

        return response()->json([
            'success' => true,
            'lambda_result' => $resultado,
            'arquitectura' => 'Laravel ECS + AWS Lambda',
            'descripcion' => 'El procesamiento se hace en Lambda serverless'
        ]);
    }

    public function procesarImagen(Request $request)
    {
        // Aumentar límites para archivos grandes
        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');


        $request->validate([
            'imagen' => 'required|image', // 10MB max
            'operacion' => 'sometimes|string'
        ]);

        try {
            // Subir imagen a S3 en carpeta uploads/
            $archivo = $request->file('imagen');
            $nombreOriginal = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();

            // Generar nombre único
            $nombreArchivo = 'uploads/' . Str::random(20) . '_' . time() . '.' . $extension;

            // Subir a S3
            Storage::disk('s3')->put($nombreArchivo, file_get_contents($archivo));

            // Construir URL manualmente (solución al error)
            $urlOriginal = "https://lamp-images-146009966183.s3.us-east-1.amazonaws.com/{$nombreArchivo}";

            // Nombre esperado de la imagen procesada
            $nombreProcesado = 'processed/' . pathinfo($nombreArchivo, PATHINFO_FILENAME) . '_processed.jpg';
            $urlProcesado = "https://lamp-images-146009966183.s3.us-east-1.amazonaws.com/{$nombreProcesado}";

            return response()->json([
                'success' => true,
                'mensaje' => 'Imagen subida a S3. Lambda la está procesando...',
                'archivo_original' => $nombreOriginal,
                's3_key_original' => $nombreArchivo,
                's3_key_procesado' => $nombreProcesado,
                'url_original' => $urlOriginal,
                'url_procesado' => $urlProcesado,
                'operacion' => $request->operacion ?? 'redimensionar',
                'procesado_por' => 'AWS Lambda + Sharp',
                'timestamp' => now()->toISOString(),
                'nota' => 'La imagen procesada estará disponible en 5-10 segundos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'mensaje' => 'Error subiendo imagen a S3'
            ], 500);
        }
    }

    public function obtenerImagenProcesada($nombreArchivo)
    {
        try {
            $s3Key = 'processed/' . $nombreArchivo;

            // Verificar si existe el archivo en S3
            if (Storage::disk('s3')->exists($s3Key)) {
                $urlImagen = "https://lamp-images-146009966183.s3.us-east-1.amazonaws.com/{$s3Key}";

                return response()->json([
                    'success' => true,
                    'url_imagen' => $urlImagen,
                    'nombre_archivo' => $nombreArchivo,
                    'disponible' => true
                ]);
            }

            return response()->json([
                'success' => false,
                'mensaje' => 'Imagen procesada no encontrada',
                'disponible' => false
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
