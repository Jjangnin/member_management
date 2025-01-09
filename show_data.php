<?php
// db_config.php 파일을 포함하여 DB 연결
include('db_config.php');

// SP 테이블에서 데이터를 조회하는 SQL 쿼리
$sql = "SELECT * FROM SP";

// 쿼리 실행
$result = $conn->query($sql);

// 데이터가 있을 경우
if ($result->num_rows > 0) {
    // 데이터 출력
    echo "<table border='1'>
            <tr>
                <th>S#</th>
                <th>P#</th>
                <th>QTY</th>
            </tr>";

    // 각 행을 출력
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["S#"]) . "</td>
                <td>" . htmlspecialchars($row["P#"]) . "</td>
                <td>" . htmlspecialchars($row["QTY"]) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// 연결 종료
$conn->close();
?>
