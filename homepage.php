<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก</title>
    <link rel="stylesheet" href="style.css"> <!-- สไตล์ที่ต้องการ -->
</head>
<body>

    <!-- Header -->
    <header>
        <h1>ระบบสถิติรางวัล</h1>
    </header>

    <div class="container">
        <!-- เมนูด้านซ้าย -->
        <nav class="sidebar">
            <ul>
                <li><a href="lot_six.php">สถิติรางวัลที่ 1</a></li>
                <li><a href="stat3.php">สถิติรางวัลเลขหน้า 3 ตัว</a></li>
                <li><a href="stat4.php">สถิติรางวัลเลขท้าย 3 ตัว</a></li>
                <li><a href="lot_two.php">สถิติรางวัลเลขท้าย 2 ตัว</a></li>
                <li><a href="view.php">ดูข้อมูล</a></li>
                <li><a href="form.php">กรอกข้อมูล</a></li>
            </ul>
        </nav>

        <!-- ส่วนกลางแสดงข้อมูล -->
        <main class="main-content">
            <h2>เลขรางวัลที่ออกล่าสุด</h2>
            <table>
                <thead>
                    <tr>
                        <th>ประเภทรางวัล</th>
                        <th>หมายเลข</th>
                        <th>วันที่ออก</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ตัวอย่างข้อมูลเลขรางวัล -->
                    <tr>
                        <td>รางวัลที่ 1</td>
                        <td>123456</td>
                        <td>2024-09-25</td>
                    </tr>
                    <tr>
                        <td>เลขหน้า 3 ตัว</td>
                        <td>789</td>
                        <td>2024-09-25</td>
                    </tr>
                    <tr>
                        <td>เลขท้าย 3 ตัว</td>
                        <td>456</td>
                        <td>2024-09-25</td>
                    </tr>
                    <tr>
                        <td>เลขท้าย 2 ตัว</td>
                        <td>12</td>
                        <td>2024-09-25</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 ระบบสถิติรางวัล. สงวนลิขสิทธิ์.</p>
    </footer>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #e9ecef;
            padding: 15px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar li {
            margin: 10px 0;
        }
        .sidebar a {
            text-decoration: none;
            color: #007bff;
        }
        .main-content {
            flex-grow: 1;
            padding: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f1f1f1;
        }
    </style>

</body>
</html>
