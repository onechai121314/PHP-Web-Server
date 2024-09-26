<?php
// fetch_data.php

// เชื่อมต่อกับฐานข้อมูล SQLite
$db = new SQLite3('data.db');

// สร้างตัวแปรสำหรับเก็บข้อมูล
$data = [];

// ดึงข้อมูลจากตาราง six_digit_numbers
$query1 = "SELECT number, date FROM six_digit_numbers ORDER BY date ASC";
$result1 = $db->query($query1);

while ($row = $result1->fetchArray(SQLITE3_ASSOC)) {
    $data[] = [
        'type' => 'six_digit',
        'number' => $row['number'],
        'date' => $row['date']
    ];
}

// ดึงข้อมูลจากตาราง two_digit_numbers
$query2 = "SELECT number, date FROM two_digit_numbers ORDER BY date ASC";
$result2 = $db->query($query2);

while ($row = $result2->fetchArray(SQLITE3_ASSOC)) {
    $data[] = [
        'type' => 'two_digit',
        'number' => $row['number'],
        'date' => $row['date']
    ];
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$db->close();

// ส่งข้อมูลในรูปแบบ JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Financial Chart</h1>
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
        async function fetchData() {
            const response = await fetch('fetch_data.php');
            const data = await response.json();

            // เตรียมข้อมูลสำหรับ Chart.js
            const labels = [...new Set(data.map(item => item.date))]; // วันที่ที่ไม่ซ้ำ
            const sixDigitNumbers = [];
            const twoDigitNumbers = [];

            // เตรียมข้อมูลตามประเภท
            labels.forEach(label => {
                const sixDigitEntry = data.find(item => item.type === 'six_digit' && item.date === label);
                const twoDigitEntry = data.find(item => item.type === 'two_digit' && item.date === label);
                
                sixDigitNumbers.push(sixDigitEntry ? sixDigitEntry.number : null); // ถ้าไม่มีให้ใส่ null
                twoDigitNumbers.push(twoDigitEntry ? twoDigitEntry.number : null); // ถ้าไม่มีให้ใส่ null
            });

            // สร้างกราฟ
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Six-Digit Numbers',
                            data: sixDigitNumbers,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Two-Digit Numbers',
                            data: twoDigitNumbers,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: false
                        }
                    ]
                },
                options: {
    responsive: true,
    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += context.parsed.y;
                    return label;
                }
            }
        }
    },
    scales: {
        x: {
            title: {
                display: true,
                text: 'Date'
            }
        },
        y: {
            title: {
                display: true,
                text: 'Numbers'
            }
        }
    }
}

            });
        }

        // ดึงข้อมูลและสร้างกราฟ
        fetchData();
    </script>
</body>
</html>

