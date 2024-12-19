<?php
function sendResponse($status, $message, $httpCode = 200, $data = null) {
    header("Content-Type: application/json");
    http_response_code($httpCode);
    $response = [
        'status' => $status,
        'message' => $message,
        'error_code' => $httpCode
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}
?>
