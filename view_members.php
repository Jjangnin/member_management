<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");  // 로그인되지 않으면 로그인 페이지로 리디렉션
    exit();
}

// DB 연결 설정
$mysqli = new mysqli("localhost", "root", "", "member_db");

if ($mysqli->connect_error) {
    die("DB 연결 실패: " . $mysqli->connect_error);
}

// 회원 정보 조회 쿼리
$sql = "SELECT username, name, gender, email, profile_video FROM members";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 목록</title>
    <style>
        /* 스타일 코드 */
    </style>
</head>
<body>
    <h2>회원 목록</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>아이디</th>
                <th>이름</th>
                <th>성별</th>
                <th>이메일</th>
                <th>프로필 영상</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender'] == 'male' ? '남성' : '여성'); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php if ($row['profile_video']): ?>
                            <video controls>
                                <source src="<?php echo htmlspecialchars($row['profile_video']); ?>" type="video/mp4">
                                영상 재생을 지원하지 않는 브라우저입니다.
                            </video>
                        <?php else: ?>
                            없음
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>등록된 회원이 없습니다.</p>
    <?php endif; ?>

    <?php
    // DB 연결 종료
    $mysqli->close();
    ?>
</body>
</html>
