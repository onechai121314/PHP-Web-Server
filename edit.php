<?php
// edit.php

$db = new SQLite3('data.db');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM six_digit_numbers WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
}

if (isset($_POST['update'])) {
    $number = $_POST['number_input'];
    $date = $_POST['date_input_date'];

    $update_query = "UPDATE six_digit_numbers SET number = :number, date = :date WHERE id = :id";
    $stmt = $db->prepare($update_query);
    $stmt->bindValue(':number', $number, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    // Redirect to view page
    header("Location: view.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#date_input_date", {
                dateFormat: "d/m/Y", // กำหนดรูปแบบวันที่เป็น DD/MM/YYYY
                allowInput: true, // อนุญาตให้กรอกวันที่ได้
                defaultDate: "<?php echo htmlspecialchars($row['date']); ?>", // ตั้งค่าเป็นวันที่ที่มีอยู่
            });
        });
        
        function validateDate(input) {
            const datePattern = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d\d$/;
            if (input.value && !datePattern.test(input.value)) {
                alert("กรุณากรอกวันที่ในรูปแบบ DD/MM/YYYY");
                input.value = "";
            }
        }
    </script>
</head>
<body>
    <h1>แก้ไขข้อมูลตัวเลข</h1>
    <form method="post" action="edit.php?id=<?php echo $id; ?>">
        <label for="number_type">เลือกประเภทตัวเลข:</label>
        <select id="number_type" name="number_type" required>
            <option value="">-- กรุณาเลือก --</option>
            <option value="six_digit" selected>ตัวเลข 6 หลัก</option>
            <option value="two_digit">ตัวเลข 2 หลัก</option>
            <option value="first_three_digit">ตัวเลข 3 หลักหน้า</option>
            <option value="last_three_digit">ตัวเลข 3 หลักท้าย</option>
        </select>
        <br><br>
        
        <label for="number_input">หมายเลข:</label>
        <input type="number" id="number_input" name="number_input" value="<?php echo htmlspecialchars($row['number']); ?>" required>
        <br><br>
        
        <label for="date_input">วันที่ (DD/MM/YYYY):</label>
        <input type="text" id="date_input_date" name="date_input_date" placeholder="DD/MM/YYYY" onblur="validateDate(this)" required>
        <br><br>
        
        <input type="submit" name="update" value="อัปเดตข้อมูล">
    </form>
    <a href="view.php">กลับไปยังหน้าหลัก</a>
</body>
</html>
