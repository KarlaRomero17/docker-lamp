<?php
require_once '/opt/bootstrap.php';

class MyHandler {
    public function handle($event) {
        $data = $event['data'] ?? 'No data';
        $action = $event['action'] ?? 'process';
        
        switch($action) {
            case 'uppercase':
                $result = strtoupper($data);
                break;
            case 'reverse':
                $result = strrev($data);
                break;
            case 'count':
                $result = strlen($data);
                break;
            default:
                $result = "Processed: " . $data;
        }
        
        return [
            'statusCode' => 200,
            'body' => json_encode([
                'input' => $data,
                'action' => $action,
                'result' => $result,
                'processed_by' => 'AWS Lambda + PHP',
                'timestamp' => date('Y-m-d H:i:s'),
                'php_version' => PHP_VERSION
            ])
        ];
    }
}

return new MyHandler();
