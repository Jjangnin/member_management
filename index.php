<?php
// MySQL 데이터베이스 연결 설정
$servername = "localhost"; // MySQL 서버 주소
$username = "root"; // MySQL 사용자 이름
$password = ""; // MySQL 비밀번호
$dbname = "CSV_DB 5"; // 사용할 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 데이터 초기화
$dates = [];
$actualSales = [];
$randomForestPredictions = [];
$svrPredictions = [];
$vehicleClassSales = [];

// 데이터베이스에서 실제 판매량 가져오기
$sql = "SELECT `Date`, EV_Sales_Quantity, Vehicle_Class FROM ev_dataset"; // 컬럼명을 실제 컬럼명으로 수정
$result = $conn->query($sql);

// 쿼리 실행 오류 확인
if ($result === false) {
    die("Error in query execution: " . $conn->error); // 쿼리 오류 출력
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 날짜 포맷을 'YYYY-MM'으로 변경
        $date = strtotime($row['Date']);
        if ($date !== false) {
            $dateFormatted = date("Y-m", $date);
        } else {
            $dateFormatted = 'Invalid Date'; // 날짜 형식이 잘못된 경우 처리
        }

        $sales = (float)$row['EV_Sales_Quantity']; // 판매량
        $vehicleClass = $row['Vehicle_Class']; // 차량 클래스

        // 날짜 및 판매량 저장
        $dates[] = $dateFormatted;
        $actualSales[] = $sales;

        // 차량 클래스별 판매량 집계
        if (!isset($vehicleClassSales[$vehicleClass])) {
            $vehicleClassSales[$vehicleClass] = 0;
        }
        $vehicleClassSales[$vehicleClass] += $sales;
    }
} else {
    echo "0 results";
}

// 예측 결과를 DB에서 가져오기 (예를 들어, `predicted_sales` 테이블에 저장된 예측 데이터를 가져오는 경우)
$predictedSql = "SELECT `Prediction_Date`, `Random Forest Predictions`, `SVR Predictions` FROM predicted_sales_table"; // 예측 판매량 쿼리
$predictedResult = $conn->query($predictedSql);

// 예측 쿼리 실행 오류 확인
if ($predictedResult === false) {
    die("Error in predicted sales query execution: " . $conn->error); // 예측 쿼리 오류 출력
}

if ($predictedResult->num_rows > 0) {
    while ($row = $predictedResult->fetch_assoc()) {
        // 예측된 날짜 포맷을 'YYYY-MM'으로 변경
        $date = strtotime($row['Prediction_Date']);
        if ($date !== false) {
            $dateFormatted = date("Y-m", $date);
        } else {
            $dateFormatted = 'Invalid Date'; // 날짜 형식이 잘못된 경우 처리
        }

        // 예측 판매량 매핑
        if (!isset($randomForestPredictions[$dateFormatted])) {
            $randomForestPredictions[$dateFormatted] = (float)$row['Random Forest Predictions']; // 랜덤 포레스트 예측 판매량
        }
        if (!isset($svrPredictions[$dateFormatted])) {
            $svrPredictions[$dateFormatted] = (float)$row['SVR Predictions']; // SVR 예측 판매량
        }
    }
} else {
    echo "No predicted sales data found.";
}

// 예측된 날짜 값이 null인 경우 랜덤하게 날짜를 할당
foreach ($randomForestPredictions as $key => $value) {
    if (is_null($value)) {
        // Null 값에 랜덤 날짜를 추가합니다
        $randomDate = date("Y-m", strtotime("+".rand(1, 30)." days"));
        $randomForestPredictions[$key] = $randomDate; // Random Date 삽입
    }
}

// JSON으로 변환
$vehicleClassSalesJSON = json_encode($vehicleClassSales);
$datesJSON = json_encode($dates);
$actualSalesJSON = json_encode($actualSales);
$randomForestPredictionsJSON = json_encode($randomForestPredictions); // 랜덤 포레스트 예측 판매량 JSON 변환
$svrPredictionsJSON = json_encode($svrPredictions); // SVR 예측 판매량 JSON 변환

// 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV Sales Visualization</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 1000px; /* 그래프의 최대 너비를 1000px로 설정 */
            width: 100%;      /* 그래프의 너비를 100%로 설정 */
            height: 500px;    /* 그래프의 높이를 500px로 설정 */
            margin: auto;
        }
        h2 {
            font-size: 1.5em; /* 제목 글씨 크기 조정 */
        }
    </style>
</head>
<body>

    <h2>EV Sales Prediction</h2>
    <canvas id="lineChart"></canvas>

    <h2>EV Sales by Vehicle Class</h2>
    <select id="vehicleClassSelector">
        <option value="">Select Vehicle Class</option>
        <?php
        // 차량 클래스 목록을 드롭다운에 추가
        foreach (array_keys($vehicleClassSales) as $vehicleClass) {
            echo "<option value=\"$vehicleClass\">$vehicleClass</option>";
        }
        ?>
    </select>
    <canvas id="barChart"></canvas>

    <script>
        const dates = <?php echo $datesJSON; ?>;
        const actualSales = <?php echo $actualSalesJSON; ?>;
        const randomForestPredictions = <?php echo $randomForestPredictionsJSON; ?>;
        const svrPredictions = <?php echo $svrPredictionsJSON; ?>;
        const vehicleClassSales = <?php echo $vehicleClassSalesJSON; ?>;

        // 꺾은선 그래프 생성
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Sales',
                        data: actualSales,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 3, // 선 두께를 3으로 설정
                        fill: false
                    }
                   
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12 // y축 글씨 크기 조정
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12 // x축 글씨 크기 조정
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 12 // 범례 글씨 크기 조정
                            }
                        }
                    }
                }
            }
        });

        // 막대 그래프 생성
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(vehicleClassSales), // 차량 클래스 이름을 x축에 표시
                datasets: [{
                    label: 'Sales Quantity',
                    data: Object.values(vehicleClassSales), // 판매량을 y축에 표시
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 12 // 범례 글씨 크기 조정
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
