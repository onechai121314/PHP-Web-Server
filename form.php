<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกข้อมูลตัวเลข</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr(".date_input_date", {
                dateFormat: "d/m/Y", // กำหนดรูปแบบวันที่เป็น DD/MM/YYYY
                allowInput: true, // อนุญาตให้กรอกวันที่ได้
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
    <h1>กรอกข้อมูลตัวเลข</h1>
    <form method="post" action="database.php">
        <label for="number_type">เลือกประเภทตัวเลข:</label>
        <select id="number_type" name="number_type" required>
            <option value="">-- กรุณาเลือก --</option>
            <option value="six_digit">ตัวเลข 6 หลัก</option>
            <option value="two_digit">ตัวเลข 2 หลัก</option>
            <option value="first_three_digit">ตัวเลข 3 หลักหน้า</option>
            <option value="last_three_digit">ตัวเลข 3 หลักท้าย</option>
        </select>
        <br><br>

        <!-- กรอกข้อมูล 5 ตัว -->
        <div id="numbersContainer">
            <h2>กรอกข้อมูลตัวเลขและวันที่:</h2>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <label for="number_input_<?php echo $i; ?>">หมายเลข <?php echo $i; ?>:</label>
                <input type="number" id="number_input_<?php echo $i; ?>" name="number_input[]" >
                <label for="date_input_<?php echo $i; ?>">วันที่ <?php echo $i; ?> (DD/MM/YYYY):</label>
                <input type="text" class="date_input_date" id="date_input_<?php echo $i; ?>" name="date_input_date[]" placeholder="DD/MM/YYYY" onblur="validateDate(this)">
                <br><br>
            <?php endfor; ?>
        </div>

        <input type="submit" value="บันทึกข้อมูล">
    </form>
</body>
</html>
