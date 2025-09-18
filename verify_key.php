<?php
header('Content-Type: application/json');

// ป้องกัน Cache
header('Cache-Control: no-cache, must-revalidate');

// โหลดไฟล์ JSON เก็บ Key
$file = __DIR__ . '/active_keys.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$keysData = json_decode(file_get_contents($file), true);

// รับค่าจาก Roblox Script
$key = isset($_GET['key']) ? $_GET['key'] : '';
$hwid = isset($_GET['hwid']) ? $_GET['hwid'] : '';

// ตรวจสอบ Key
if (isset($keysData[$key])) {
    $keyData = $keysData[$key];

    // เช็คว่า Key หมดอายุหรือยัง
    if (time() > $keyData['expire']) {
        unset($keysData[$key]); // ลบ Key ที่หมดอายุ
        file_put_contents($file, json_encode($keysData));
        echo json_encode(["status" => "invalid", "message" => "Key หมดอายุแล้ว!"]);
        exit;
    }

    // เช็คว่า Key ถูกใช้งานแล้วหรือยัง
    if ($keyData['hwid'] === null) {
        $keysData[$key]['hwid'] = $hwid; // ล็อก Key กับ HWID
        file_put_contents($file, json_encode($keysData));
    } elseif ($keyData['hwid'] !== $hwid) {
        echo json_encode(["status" => "invalid", "message" => "Key นี้ถูกใช้ในเครื่องอื่นแล้ว!"]);
        exit;
    }

    // Key ถูกต้อง
    echo json_encode(["status" => "valid"]);
} else {
    echo json_encode(["status" => "invalid", "message" => "Key ไม่ถูกต้อง!"]);
}
?>
