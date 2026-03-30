<?php
header('Content-Type: application/json');

$uploadDir = __DIR__ . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No image received']);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Upload error: ' . $file['error']]);
    exit;
}

if ($file['size'] === 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Empty file']);
    exit;
}

$filename = 'cam1_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
$filepath = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode([
        'status'   => 'success',
        'message'  => 'Image uploaded successfully',
        'filename' => $filename,
        'size'     => $file['size']
    ]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
}
?>
