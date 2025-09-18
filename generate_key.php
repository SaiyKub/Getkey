<?php
// ตั้งค่า Key หมดอายุ (24 ชั่วโมง)
$expire_time = 24 * 60 * 60;

$file = __DIR__ . '/active_keys.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$keysData = json_decode(file_get_contents($file), true);

// สร้าง Key แบบสุ่ม
$key = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);

$keysData[$key] = [
    "hwid" => null, // ยังไม่ผูกกับเครื่องใด
    "expire" => time() + $expire_time
];

file_put_contents($file, json_encode($keysData));

echo "New Key Generated: " . $key;
?>
