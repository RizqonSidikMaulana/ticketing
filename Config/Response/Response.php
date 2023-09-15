<?php
namespace App\Config\Response;

class Response {
    function response($payload)
    {
        $payload = [
            'meta' => [
                'status' => $payload['status'],
                'message' => $payload['message'],
            ],
            'data'=> $payload['data']
        ];

        // Mengembalikan nilai array sebagai respons
        header('Content-Type: application/json');
        echo json_encode($payload);
    }
}
