<?php
// database.php

// สร้างฐานข้อมูลและเชื่อมต่อ
$db = new SQLite3('data.db');

// สร้างตารางสำหรับเก็บตัวเลข 6 หลัก
$query1 = "CREATE TABLE IF NOT EXISTS six_digit_numbers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number INTEGER CHECK(number BETWEEN 00000 AND 999999),
    date TEXT
)";
$db->exec($query1);

// สร้างตารางสำหรับเก็บตัวเลข 2 หลัก
$query2 = "CREATE TABLE IF NOT EXISTS two_digit_numbers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number INTEGER CHECK(number BETWEEN 00 AND 99),
    date TEXT
)";
$db->exec($query2);

// สร้างตารางสำหรับเก็บตัวเลข 3 หน้า
$query3 = "CREATE TABLE IF NOT EXISTS first_three_digit_numbers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number INTEGER CHECK(number BETWEEN 000 AND 999),
    date TEXT
)";
$db->exec($query3);

// สร้างตารางสำหรับเก็บตัวเลข 3 ท้าย
$query4 = "CREATE TABLE IF NOT EXISTS last_three_digit_numbers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number INTEGER CHECK(number BETWEEN 000 AND 999),
    date TEXT
)";
$db->exec($query4);

// ฟังก์ชันสำหรับเพิ่มตัวเลข 6 หลัก
function insertSixDigitNumber($db, $number, $date) {
    $stmt = $db->prepare('INSERT INTO six_digit_numbers (number, date) VALUES (:number, :date)');
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    return $stmt->execute();
}

// ฟังก์ชันสำหรับเพิ่มตัวเลข 2 หลัก
function insertTwoDigitNumber($db, $number, $date) {
    $stmt = $db->prepare('INSERT INTO two_digit_numbers (number, date) VALUES (:number, :date)');
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    return $stmt->execute();
}

// ฟังก์ชันสำหรับเพิ่มตัวเลข 3 หลักหน้า
function insertFirstThreeDigitNumber($db, $number, $date) {
    $stmt = $db->prepare('INSERT INTO first_three_digit_numbers (number, date) VALUES (:number, :date)');
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    return $stmt->execute();
}

// ฟังก์ชันสำหรับเพิ่มตัวเลข 3 หลักท้าย
function insertLastThreeDigitNumber($db, $number, $date) {
    $stmt = $db->prepare('INSERT INTO last_three_digit_numbers (number, date) VALUES (:number, :date)');
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    return $stmt->execute();
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number_type = $_POST['number_type'];
    $number_inputs = $_POST['number_input'];
    $date_inputs = $_POST['date_input_date'];

    // แปลงวันที่และบันทึกข้อมูล
    foreach ($number_inputs as $index => $number_input) {
        $number_input = intval($number_input);
        $date_input = $date_inputs[$index] ?? null; // รับวันที่ที่ตรงกัน

        if ($date_input) {
            // แปลงวันที่เป็น YYYY-MM-DD
            if (strpos($date_input, '/') !== false) {
                $date_parts = explode('/', $date_input);
                $date_input = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
            }

            // บันทึกข้อมูลตามประเภท
            switch ($number_type) {
                case 'six_digit':
                    insertSixDigitNumber($db, $number_input, $date_input);
                    break;
                case 'two_digit':
                    insertTwoDigitNumber($db, $number_input, $date_input);
                    break;
                case 'first_three_digit':
                    insertFirstThreeDigitNumber($db, $number_input, $date_input);
                    break;
                case 'last_three_digit':
                    insertLastThreeDigitNumber($db, $number_input, $date_input);
                    break;
            }
        }
    }

    // ปิดการเชื่อมต่อ
    $db->close();

    // แสดงข้อความสำเร็จ
    echo "บันทึกข้อมูลเรียบร้อย!";
}
?>
