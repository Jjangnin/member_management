async function fetchData() {
    try {
        // 파이썬에서 예측한 CSV 파일 경로로 변경
        const response = await fetch('http://localhost/myproject/predicted_sales.csv'); 
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const csvData = await response.text();

        // CSV 파싱
        const parsedData = Papa.parse(csvData, { header: true });

        const models = new Set();
        const salesData = {};
        const overallSalesData = {}; // 전체 판매량 데이터 초기화

        parsedData.data.forEach(record => {
            const model = record.Vehicle_Class; // 차량 모델
            const predictedSales = parseFloat(record.Predicted_Sales_Quantity) || 0; // 예측된 판매량
            const date = record.Date;

            // 모델 목록 추가
            models.add(model);

            // 모델별 판매량 데이터 초기화
            if (!salesData[model]) {
                salesData[model] = { dates: [], predictedSales: [] }; // 'predictedSales'로 수정
            }
            salesData[model].dates.push(date);
            salesData[model].predictedSales.push(predictedSales); // 예측 판매량 추가

            // 전체 판매량 집계 (여기서는 예측 판매량이므로 주의)
            if (!overallSalesData[date]) {
                overallSalesData[date] = 0;
            }
            overallSalesData[date] += predictedSales; // 예측 판매량 누적
        });

        // 드롭다운 메뉴에 모델 추가
        const modelSelect = document.getElementById('modelSelect');
        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model;
            option.textContent = model;
            modelSelect.appendChild(option);
        });

        // 드롭다운 선택에 따라 그래프 업데이트
        modelSelect.addEventListener('change', (event) => {
            const selectedModel = event.target.value;
            updateChart(salesData[selectedModel]);
        });

        // 초기 그래프 표시
        const firstModel = Array.from(models)[0]; // 첫 번째 모델 가져오기
        modelSelect.value = firstModel; // 드롭다운 초기값 설정
        updateChart(salesData[firstModel]); // 초기 그래프 표시

        // 전체 판매량 그래프 표시
        updateOverallSalesChart(overallSalesData);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

let lineChart; // 차트 변수를 전역으로 선언

function updateChart(modelData) {
    const lineCtx = document.getElementById('lineChart').getContext('2d');

    // 이전 그래프가 있다면 지우기
    if (lineChart) {
        lineChart.destroy(); // 차트가 있다면 파괴
    }

    // 새로운 차트 생성
    lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: modelData.dates,
            datasets: [
                {
                    label: 'Predicted Sales Quantity',
                    data: modelData.predictedSales,
                    borderColor: 'rgba(255, 99, 132, 1)',
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
                            size: 10 // y축 글씨 크기 조정
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10 // x축 글씨 크기 조정
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 10 // 범례 글씨 크기 조정
                        }
                    }
                }
            }
        }
    });
}

// 전체 판매량 추이를 보여주는 꺾은선 그래프 업데이트
let overallChart; // 전체 판매량 차트 변수를 전역으로 선언

function updateOverallSalesChart(overallSalesData) {
    const overallCtx = document.getElementById('overallSalesChart').getContext('2d');

    // 이전 그래프가 있다면 지우기
    if (overallChart) {
        overallChart.destroy(); // 차트가 있다면 파괴
    }

    // 날짜와 판매량 배열 준비
    const dates = Object.keys(overallSalesData);
    const sales = Object.values(overallSalesData);

    // 새로운 차트 생성
    overallChart = new Chart(overallCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Total Predicted Sales Quantity',
                data: sales,
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10 // y축 글씨 크기 조정
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10 // x축 글씨 크기 조정
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 10 // 범례 글씨 크기 조정
                        }
                    }
                }
            }
        }
    });
}

fetchData();
