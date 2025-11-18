<?php
namespace App\Services;

class LambdaService
{
    public function procesarConLambda($texto, $accion = 'uppercase')
    {
        // SIMULACIÓN - En producción usarías AWS SDK para Lambda
        $resultadoSimulado = [
            'statusCode' => 200,
            'body' => json_encode([
                'success' => true,
                'input' => $texto,
                'action' => $accion,
                'result' => $this->simularProcesamiento($texto, $accion),
                'processed_by' => 'AWS Lambda (simulado)',
                'timestamp' => now()->toISOString(),
                'message' => '¡Integración Laravel + Lambda exitosa!'
            ])
        ];

        return json_decode($resultadoSimulado['body'], true);
    }

    private function simularProcesamiento($texto, $accion)
    {
        switch($accion) {
            case 'uppercase': return strtoupper($texto);
            case 'reverse': return strrev($texto);
            case 'count': return "Longitud: " . strlen($texto);
            case 'words': return "Palabras: " . str_word_count($texto);
            default: return $texto;
        }
    }
}
