<?php
$target_dir = "uploads/";

// 1. Get Trap ID (e.g., Trap_01)
$trap_id = isset($_POST['trap_id']) ? $_POST['trap_id'] : 'Unknown_Trap';

// 2. Generate Unique Filename (TrapID_Date_Time.jpg)
$date = date("Ymd_His");
$filename = $trap_id . "_" . $date . ".jpg";
$target_file = $target_dir . $filename;

// 3. Process the Upload
if (isset($_FILES["image"])) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Log success for your ESP32 to see in the Serial Monitor
        echo "SUCCESS: $filename uploaded.";
    } else {
        echo "ERROR: Could not move file to uploads folder.";
    }
} else {
    echo "ERROR: No image data detected in POST request.";
}
?>
