<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// DB 연결
include('db_config.php');

// 사용자 이름 확인
$username = $_SESSION['username'];

// SNAME 드롭다운에서 선택된 값 받기
$selected_sname = isset($_POST['sname']) ? $_POST['sname'] : '';

// SNAME 값에 따라 PNAME, COLOR, QTY를 조회하는 SQL 쿼리
$sql = "SELECT p.PNAME, p.COLOR, sp.QTY 
        FROM S s
        JOIN P p ON s.city = p.city
        JOIN SP sp ON s.`s#` = sp.`s#`
        WHERE s.SNAME = ?";

// 부품 총 수량을 계산하는 SQL 쿼리
$sum_sql = "SELECT SUM(sp.QTY) AS total_qty
            FROM S s
            JOIN SP sp ON s.`s#` = sp.`s#`
            WHERE s.SNAME = ?";

// 준비된 문장 실행 (부품 정보 조회)
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('쿼리 준비 실패: ' . $conn->error);
}

// 선택된 SNAME을 쿼리 파라미터로 바인딩
$stmt->bind_param("s", $selected_sname);
$stmt->execute();
$result = $stmt->get_result();

// 준비된 문장 실행 (부품 총 수량 조회)
$stmt_sum = $conn->prepare($sum_sql);
if ($stmt_sum === false) {
    die('쿼리 준비 실패: ' . $conn->error);
}

// 선택된 SNAME을 쿼리 파라미터로 바인딩
$stmt_sum->bind_param("s", $selected_sname);
$stmt_sum->execute();
$sum_result = $stmt_sum->get_result();
$sum_row = $sum_result->fetch_assoc();
$total_qty = $sum_row['total_qty'];

// 결과 확인 (디버깅용)
if ($result === false || $sum_result === false) {
    die('쿼리 실행 실패: ' . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>대시보드</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>환영합니다, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>님!</h1>

        <!-- SNAME 선택 드롭다운 -->
        <form method="POST" action="dashboard.php">
            <label for="sname">SNAME 선택: </label>
            <select name="sname" id="sname">
                <?php
                // SNAME 목록을 조회하여 드롭다운에 표시
                $sname_sql = "SELECT DISTINCT SNAME FROM S";  // SNAME 목록을 조회
                $sname_result = $conn->query($sname_sql);
                if ($sname_result) {
                    while ($sname = $sname_result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($sname['SNAME'], ENT_QUOTES, 'UTF-8') . "'" . ($sname['SNAME'] == $selected_sname ? " selected" : "") . ">" . htmlspecialchars($sname['SNAME'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                } else {
                    echo "<option disabled>SNAME 목록을 불러올 수 없습니다.</option>";
                }
                ?>
            </select>
            <button type="submit">선택</button>
        </form>

        <!-- 선택된 SNAME에 따른 부품 및 수량 테이블 출력 -->
        <h2>부품 목록</h2>
        <?php
        if ($selected_sname != '') {
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>부품 이름</th>
                            <th>색상</th>
                            <th>수량</th>
                        </tr>";
                
                // 부품 이름, 색상, 수량 출력
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['PNAME'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['COLOR'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['QTY'], ENT_QUOTES, 'UTF-8') . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "선택된 SNAME에 해당하는 부품 정보가 없습니다.";
            }

            // 총 수량 이미지로 출력
            echo "<h3>총 부품 수량: ";
            $total_qty_str = strval($total_qty); // 숫자를 문자열로 변환
            $len = strlen($total_qty_str); // 숫자의 길이 (몇 자릿수인지)

            // 총 부품 수량을 이미지로 출력
            for ($i = 0; $i < $len; $i++) {
                // 숫자에 맞는 이미지를 출력 (예: '0.gif', '1.gif', ...)
                echo "<img src='image/" . $total_qty_str[$i] . ".png' alt='" . $total_qty_str[$i] . " 이미지'>";
            }
            echo "</h3>";
        }
        ?>

        <!-- 로그아웃 링크 -->
        <a href="logout.php">로그아웃</a>
    </div>
</body>
</html>

<?php
// DB 연결 종료
$conn->close();
?>
