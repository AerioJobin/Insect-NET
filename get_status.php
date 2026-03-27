<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$device_id  = $_GET['device_id'] ?? null;
$upload_dir = 'uploads/';
$status_dir = 'status/';   // where devices POST their telemetry JSON

if (!$device_id) {
    echo json_encode(['status' => 'error', 'message' => 'No device_id']);
    exit;
}

// Sanitize
$device_id = preg_replace('/[^a-z0-9_\-]/', '', $device_id);

// ── Try reading from a status JSON file that the device pushes ──
// Expected file: status/cam1.json  (or device1.json — adjust to your setup)
$status_file = $status_dir . $device_id . '.json';

if (is_file($status_file)) {
    $raw = file_get_contents($status_file);
    $data = json_decode($raw, true);
    if ($data) {
        echo json_encode([
            'status' => 'success',
            'latest' => [
                'battery_voltage' => $data['battery_voltage'] ?? $data['batt'] ?? null,
                'gps_latitude'    => $data['gps_latitude']    ?? $data['lat']  ?? null,
                'gps_longitude'   => $data['gps_longitude']   ?? $data['lng']  ?? null,
                'timestamp'       => $data['timestamp']        ?? filemtime($status_file),
            ]
        ]);
        exit;
    }
}

// ── Fallback: derive last-seen from most recent uploaded image ──
$files  = glob($upload_dir . $device_id . '_*.{jpg,jpeg,png}', GLOB_BRACE) ?: [];
if ($files) {
    $lastTs = max(array_map('filemtime', $files));
    echo json_encode([
        'status' => 'success',
        'latest' => [
            'battery_voltage' => null,
            'gps_latitude'    => null,
            'gps_longitude'   => null,
            'timestamp'       => $lastTs,
        ]
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'No data for device']);
