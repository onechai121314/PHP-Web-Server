<?php
// view.php

// สร้างฐานข้อมูลและเชื่อมต่อ
$db = new SQLite3('data.db');

// ดึงข้อมูลตัวเลข 6 หลัก
$six_digit_query = "SELECT * FROM six_digit_numbers ORDER BY date DESC";
$six_digit_result = $db->query($six_digit_query);

// ดึงข้อมูลตัวเลข 2 หลัก
$two_digit_query = "SELECT * FROM two_digit_numbers ORDER BY date DESC";
$two_digit_result = $db->query($two_digit_query);

// ดึงข้อมูลตัวเลข 3 หลักหน้า
$first_three_digit_query = "SELECT * FROM first_three_digit_numbers ORDER BY date DESC";
$first_three_digit_result = $db->query($first_three_digit_query);

// ดึงข้อมูลตัวเลข 3 หลักท้าย
$last_three_digit_query = "SELECT * FROM last_three_digit_numbers ORDER BY date DESC";
$last_three_digit_result = $db->query($last_three_digit_query);

// ตรวจสอบการลบข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $ids_to_delete = $_POST['ids'] ?? [];
    foreach ($ids_to_delete as $id) {
        $db->exec("DELETE FROM six_digit_numbers WHERE id = $id");
        $db->exec("DELETE FROM two_digit_numbers WHERE id = $id");
        $db->exec("DELETE FROM first_three_digit_numbers WHERE id = $id");
        $db->exec("DELETE FROM last_three_digit_numbers WHERE id = $id");
        // ลบจากตารางอื่น ๆ ตามต้องการ
    }
    header("Location: view.php"); // โหลดใหม่เพื่อไม่ให้มีการส่งฟอร์มซ้ำ
    exit;
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูข้อมูลตัวเลข</title>
</head>
<body>
    <h1>ข้อมูลตัวเลข 6 หลัก</h1>
     <form method="POST">
        <table border="1">
            <tr>
                <th><input type="checkbox" onclick="toggle(this)"></th>
                <th>ID</th>
                <th>หมายเลข</th>
                <th>วันที่</th>
            </tr>
            <?php while ($row = $six_digit_result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo str_pad($row['number'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td><?php
                        $date = $row['date'];
                        $date_parts = explode('-', $date);
                        echo $date_parts[2] . '/' . $date_parts[1] . '/' . $date_parts[0];
                    ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" name="delete">ลบที่เลือก</button>
    </form>

    <h1>ข้อมูลตัวเลข 2 หลัก</h1>
    <form method="POST">
        <table border="1">
            <tr>
                <th><input type="checkbox" onclick="toggle(this)"></th>
                <th>ID</th>
                <th>หมายเลข</th>
                <th>วันที่</th>
            </tr>
            <?php while ($row = $two_digit_result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo str_pad($row['number'], 2, '0', STR_PAD_LEFT); ?></td>
                    <td><?php
                        $date = $row['date'];
                        $date_parts = explode('-', $date);
                        echo $date_parts[2] . '/' . $date_parts[1] . '/' . $date_parts[0];
                    ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" name="delete">ลบที่เลือก</button>
    </form>

    <h1>ข้อมูลตัวเลข 3 หลักหน้า</h1>
    <form method="POST">
        <table border="1">
            <tr>
                <th><input type="checkbox" onclick="toggle(this)"></th>
                <th>ID</th>
                <th>หมายเลข</th>
                <th>วันที่</th>
            </tr>
            <?php while ($row = $first_three_digit_result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo str_pad($row['number'], 3, '0', STR_PAD_LEFT); ?></td>
                    <td><?php
                        $date = $row['date'];
                        $date_parts = explode('-', $date);
                        echo $date_parts[2] . '/' . $date_parts[1] . '/' . $date_parts[0];
                    ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" name="delete">ลบที่เลือก</button>
    </form>

    <h1>ข้อมูลตัวเลข 3 หลักท้าย</h1>
    <form method="POST">
        <table border="1">
            <tr>
                <th><input type="checkbox" onclick="toggle(this)"></th>
                <th>ID</th>
                <th>หมายเลข</th>
                <th>วันที่</th>
            </tr>
            <?php while ($row = $last_three_digit_result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo str_pad($row['number'], 3, '0', STR_PAD_LEFT); ?></td>
                    <td><?php
                        $date = $row['date'];
                        $date_parts = explode('-', $date);
                        echo $date_parts[2] . '/' . $date_parts[1] . '/' . $date_parts[0];
                    ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" name="delete">ลบที่เลือก</button>
    </form>

    <?php
    // ปิดการเชื่อมต่อ
    $db->close();
    ?>
</body>
</html>
