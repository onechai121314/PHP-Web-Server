<?php
// lot_two.php

// สร้างฐานข้อมูลและเชื่อมต่อ
$db = new SQLite3('data.db');

// ดึงข้อมูลเลข 2 หลักจากฐานข้อมูล
$query = "SELECT number FROM two_digit_numbers";
$result = $db->query($query);

// สร้างอาร์เรย์เพื่อเก็บความถี่ของตัวเลขในแต่ละหลัก
$digitFrequencies = array_fill(0, 2, array_fill(0, 10, 0));

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $number = str_split($row['number']);
    // ตรวจสอบว่ามี 2 หลัก
    if (count($number) === 2) {
        for ($i = 0; $i < 2; $i++) {
            $digitFrequencies[$i][(int)$number[$i]]++;
        }
    }
}

$db->close();

// ฟังก์ชันคาดการณ์ตัวเลข
function predictNumbers($frequencies) {
    $predictedNumbers = [];
    foreach ($frequencies as $index => $freq) {
        $maxFrequency = max($freq);
        $predictedDigit = array_search($maxFrequency, $freq);
        if (!in_array($predictedDigit, $predictedNumbers)) {
            $predictedNumbers[] = $predictedDigit;
        }
    }
    // ตรวจสอบให้แน่ใจว่ามี 2 ตัว
    while (count($predictedNumbers) < 2) {
        for ($j = 0; $j <= 9; $j++) {
            if (!in_array($j, $predictedNumbers)) {
                $predictedNumbers[] = $j;
            }
            if (count($predictedNumbers) >= 2) {
                break;
            }
        }
    }
    return implode('', $predictedNumbers);
}

$predictedNumber = predictNumbers($digitFrequencies);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิเคราะห์เลข 2 หลัก</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #lotChart {
            max-width: 1000px;
            height: 500px;
            margin: 0 auto;
        }
        .digit-input {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>วิเคราะห์เลข 2 หลัก</h1>

    <div id="lotChart">
        <canvas id="lotChartCanvas" width="800" height="400"></canvas>
    </div>

    <form id="lotForm" onsubmit="return calculateAverage(event)">
        <label for="digits">กรอกเลข 2 หลัก:</label>
        <input type="text" id="digits" name="digits" maxlength="2" required>
        <button type="submit">คำนวณค่าเฉลี่ย</button>
        <button type="button" id="resetButton">รีเซ็ตกราฟ</button>
    </form>

    <p id="averageResult"></p>
    <p>เลขที่คาดการณ์: <strong id="predictedNumber"><?php echo $predictedNumber; ?></strong></p>

    <script>
    const digitFrequencies = <?php echo json_encode($digitFrequencies); ?>;

    const ctx = document.getElementById('lotChartCanvas').getContext('2d');
    const lotChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [...Array(10).keys()], // เลข 0-9
            datasets: digitFrequencies.map((frequencies, index) => ({
                label: `หลักที่ ${index + 1}`,
                data: frequencies,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ][index],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ][index],
                borderWidth: 1
            }))
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวนครั้ง'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'เลข'
                    }
                }
            }
        }
    });

    function calculateAverage(event) {
        event.preventDefault();
        
        const inputDigits = document.getElementById('digits').value.split('').map(Number);

        if (inputDigits.length !== 2) {
            alert('กรุณาใส่เลข 2 หลัก');
            return;
        }

        // คำนวณค่าเฉลี่ย
        const average = inputDigits.reduce((a, b) => a + b, 0) / inputDigits.length;
        const averageIndex = Math.round(average);

        document.getElementById('averageResult').innerText = `ค่าเฉลี่ย: ${average.toFixed(2)}`;

        // อัปเดตข้อมูลในกราฟ
        inputDigits.forEach(digit => {
            lotChart.data.datasets.forEach((dataset, index) => {
                if (index < inputDigits.length) {
                    dataset.data[digit] += 1; // เพิ่มความถี่ของตัวเลขที่ป้อน
                }
            });
        });

        // อัปเดตแท่งที่แสดงค่าเฉลี่ย
        lotChart.data.datasets.forEach((dataset, index) => {
            if (index === averageIndex) {
                dataset.data[averageIndex] += 1; // เพิ่มจำนวนความถี่ตามค่าเฉลี่ย
            }
        });

        lotChart.update();
    }

    document.getElementById('resetButton').addEventListener('click', () => {
        location.reload(); // โหลดหน้าใหม่
    });
    
    </script>
</body>
</html>
