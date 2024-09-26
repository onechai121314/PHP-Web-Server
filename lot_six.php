<?php
// lot_six.php

// สร้างฐานข้อมูลและเชื่อมต่อ
$db = new SQLite3('data.db');

// ดึงข้อมูลเลข 6 หลักจากฐานข้อมูล
$query = "SELECT number FROM six_digit_numbers";
$result = $db->query($query);

// สร้างอาร์เรย์เพื่อเก็บความถี่ของตัวเลขในแต่ละหลัก
$digitFrequencies = array_fill(0, 6, array_fill(0, 10, 0));

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $number = str_split($row['number']);
    // ตรวจสอบว่ามี 6 หลัก
    if (count($number) === 6) {
        for ($i = 0; $i < 6; $i++) {
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
    while (count($predictedNumbers) < 6) {
        for ($j = 0; $j <= 9; $j++) {
            if (!in_array($j, $predictedNumbers)) {
                $predictedNumbers[] = $j;
            }
            if (count($predictedNumbers) >= 6) {
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
    <title>วิเคราะห์เลข 6 หลัก</title>
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
    <h1>วิเคราะห์เลข 6 หลัก</h1>

    <div id="lotChart">
        <canvas id="lotChartCanvas" width="800" height="400"></canvas>
    </div>

    <form id="lotForm" onsubmit="return calculateAverage(event)">
        <label for="digits">กรอกเลข 6 หลัก:</label>
        <input type="text" id="digits" name="digits" maxlength="6" required>
        <button type="submit">คำนวณค่าเฉลี่ย</button>
        <button type="button" id="resetButton">รีเซ็ตกราฟ</button>
    </form>

    <!-- <h2>กรอกเลขแต่ละหลัก (ไม่บังคับ):</h2>
    <form id="singleDigitForm" onsubmit="return calculateSingleAverage(event)">
        <div>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="number" class="digit-input" min="0" max="9" id="digit_<?php echo $i; ?>">
            <?php endfor; ?>
        </div>
        <button type="submit">คำนวณค่าเฉลี่ยตามหลัก</button>
    </form> -->

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
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ][index],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
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

        if (inputDigits.length !== 6) {
            alert('กรุณาใส่เลข 6 หลัก');
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

    function calculateSingleAverage(event) {
        event.preventDefault();
        
        const averages = [];
        for (let i = 0; i < 6; i++) {
            const digit = document.getElementById(`digit_${i}`).value;
            if (digit !== '') {
                if (!averages[i]) averages[i] = [];
                averages[i].push(parseInt(digit));
            }
        }

        const averageResults = averages.map(avg => avg.length > 0 ? avg.reduce((a, b) => a + b, 0) / avg.length : 0);

        // document.getElementById('averageResult').innerText = `ค่าเฉลี่ยตามหลัก: ${averageResults.map(avg => avg.toFixed(2)).join(' | ')}`;
    }

    document.getElementById('resetButton').addEventListener('click', () => {
        location.reload(); // โหลดหน้าใหม่
    });
    
    </script>
</body>
</html>
