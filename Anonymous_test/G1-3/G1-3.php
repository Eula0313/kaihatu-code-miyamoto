<?php
session_start();
require '../db-connect.php';

// 新しいルームに入る際にニックネームとホストフラグをリセットする
if (isset($_GET['room_key']) && ($_SESSION['last_room_key'] ?? '') !== $_GET['room_key']) {
    // ルームキーが変更された場合、ホストフラグとニックネームをリセット
    unset($_SESSION['is_host']);
    unset($_SESSION['nickname']);
    $_SESSION['last_room_key'] = $_GET['room_key'];
}

// URLからroom_keyとroom_idを取得
$room_key = $_GET['room_key'] ?? '';
$room_id = $_GET['room_id'] ?? '';
$nickname = $_SESSION['nickname'] ?? '';
$is_host = $_SESSION['is_host'] ?? false;
$users = [];
$userCount = 0;
$roomStatus = '';

if (empty($room_id) && !empty($room_key)) {
    // room_keyを使ってroom_IDを取得
    try {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT room_ID FROM Room WHERE room_key = ?");
        $stmt->execute([$room_key]);
        $room_id = $stmt->fetchColumn();

        if (!$room_id) {
            throw new Exception('無効なルームキーです。');
        }
    } catch (Exception $e) {
        echo 'エラー: ' . $e->getMessage();
        exit();
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . $e->getMessage();
        exit();
    }
}

if (!$is_host && empty($nickname) && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nickname'])) {
    $nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');

    try {
        $pdo = connectDB();
        // ニックネームの重複をチェック
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE room_ID = ? AND user_name = ?");
        $stmt->execute([$room_id, $nickname]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = 'このニックネームは既に使用されています。別のニックネームを入力してください。';
            header("Location: G1-3.php?room_key=$room_key&room_id=$room_id");
            exit();
        }
        
        $stmt = $pdo->prepare("INSERT INTO User (room_ID, user_name, team_ID, role_ID) VALUES (?, ?, NULL, NULL)");
        $stmt->execute([$room_id, $nickname]);

        $_SESSION['nickname'] = $nickname;
        //追加要素
        $_SESSION['user_id'] = $pdo->lastInsertId();

        header("Location: G1-3.php?room_key=$room_key&room_id=$room_id");
        exit();
    } catch (Exception $e) {
        echo 'エラー: ' . $e->getMessage();
        $nickname = "";
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . $e->getMessage();
        exit();
    }
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT user_name, team_ID, role_ID FROM User WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $userCount = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT status FROM Room WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $roomStatus = $stmt->fetchColumn();

    // 役割ごとのユーザー情報を取得
    $stmt = $pdo->prepare("SELECT user_name, team_ID, role_ID FROM User WHERE room_ID = ? AND role_ID IS NOT NULL");
    $stmt->execute([$room_id]);
    $roleUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . $e->getMessage();
    exit();
}

$teamNames = [1 => '赤チーム', 2 => '青チーム'];
$roleNames = [1 => 'オペレーター', 2 => 'アストロノーツ'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="G1-3ver2.0.css">
    <title>Anonymous</title>
    <style>
        .disabled-button {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>
<body>
<?php require '../header/header.php';?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    window.addEventListener("DOMContentLoaded", () => {
  // 星を表示するための親要素を取得
  const stars = document.querySelector(".stars");

  // 星を生成する関数
  const createStar = () => {
    const starEl = document.createElement("span");
    starEl.className = "star";
    const minSize = 1; // 星の最小サイズを指定
    const maxSize = 3; // 星の最大サイズを指定
    const size = Math.random() * (maxSize - minSize) + minSize;
    starEl.style.width = `${size}px`;
    starEl.style.height = `${size}px`;
    starEl.style.left = `${Math.random() * 100}%`;
    starEl.style.top = `${Math.random() * 100}%`;
    starEl.style.animationDelay = `${Math.random() * 10}s`;
    stars.appendChild(starEl);
  };

  // for文で星を生成する関数を指定した回数呼び出す
  for (let i = 0; i <= 500; i++) {
    createStar();
  }
});

    $(document).ready(function() {
        const roomKey = "<?php echo htmlspecialchars($room_key); ?>";
        const roomId = "<?php echo htmlspecialchars($room_id); ?>";

        function updateUsers() {
            $.get('get_users.php', {room_key: roomKey, room_id: roomId}, function(data) {
                $('#userList').html(data);
                updateRoleButtons();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAXエラー: " + textStatus + ", " + errorThrown);
            });
        }

        function updateUserCount() {
            $.get('../count_users.php', {room_key: roomKey, room_id: roomId}, function(data) {
                $('#userCount').text(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAXエラー: " + textStatus + ", " + errorThrown);
            });
        }

        function updateRoleButtons() {
            $.get('get_role_status.php', {room_key: roomKey, room_id: roomId}, function(data) {
                const result = JSON.parse(data);
                const roles = result.roles;
                const allRolesSelected = result.allRolesSelected;

                $('.role-button').each(function() {
                    const roleId = $(this).data('role-id');
                    const teamId = $(this).data('team-id');
                    const isSelected = roles.some(role => role.team_ID == teamId && role.role_ID == roleId);
                    if (isSelected) {
                        $(this).addClass('disabled-button').prop('disabled', true);
                    } else {
                        $(this).removeClass('disabled-button').prop('disabled', false);
                    }
                });

                if (allRolesSelected) {
                    $('#startGame').show();
                } else {
                    $('#startGame').hide();
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAXエラー: " + textStatus + ", " + errorThrown);
            });
        }

        function updateRoleUsers() {
            $.get('get_role_users.php', {room_key : roomKey , room_id: roomId}, function(data) {
                const roles = JSON.parse(data);
                $('#operator-red').html('');
                $('#astronaut-red').html('');
                $('#operator-blue').html('');
                $('#astronaut-blue').html('');

                roles.forEach(function(user) {
                    if (user.team_ID == 1 && user.role_ID == 1) {
                        $('#operator-red').append('<h3>' + user.user_name + '</h3>');
                    } else if (user.team_ID == 1 && user.role_ID == 2) {
                        $('#astronaut-red').append('<h3>' + user.user_name + '</h3>');
                    } else if (user.team_ID == 2 && user.role_ID == 1) {
                        $('#operator-blue').append('<h3>' + user.user_name + '</h3>');
                    } else if (user.team_ID == 2 && user.role_ID == 2) {
                        $('#astronaut-blue').append('<h3>' + user.user_name + '</h3>');
                    }
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAXエラー: " + textStatus + ", " + errorThrown);
            });
        }

        function checkGameStart() {
            $.get('../check_game_start.php', {room_key:roomKey,room_id: roomId}, function(data) {
                if (data === 'started') {
                    window.location.href = '../G2-1/G2-1.php?room_key=' + roomKey + '&room_id=' + roomId;
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAXエラー: " + textStatus + ", " + errorThrown);
            });
        }

        function refreshData() {
            updateUsers();
            updateUserCount();
            updateRoleButtons();
            checkGameStart();
            updateRoleUsers();
        }

        setInterval(refreshData, 1000);

        $('.role-button').click(function() {
            const roleId = $(this).data('role-id');
            const teamId = $(this).data('team-id');
            $.post('../update_users.php', {room_key: roomKey , room_id: roomId, role_id: roleId, team_id: teamId}, function(response) {
                alert(response);
                refreshData();
            });
        });

        $('#startGame').click(function() {
            $.post('../start_game.php', {room_key: roomKey , room_id: roomId}, function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            });
        });

        $('#startGame').hide();
    });

</script>
<div class="stars">
    <div class="Name">
        <div class="namebox">
            <?php if (!$is_host && empty($nickname)) { ?>
                <form method="post" action="">
                    <input type="hidden" name="room_key" value="<?php echo htmlspecialchars($room_key); ?>">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                        unset($_SESSION['error_message']);
                    }
                    ?>
                    <label for="nickname">ニックネーム:</label>
                    <input type="text" id="nickname" maxlength="8" name="nickname" required>
                    <button type="submit" class="Button-style">参加</button>
                </form>
            <?php } else {
                echo "<h3>参加者: $nickname</h3>";
            } ?>
        </div>
    </div>

    
    <div class="container">
        <div class="team-box red-team">
            <h2>赤チーム</h2>
            <div class="photo-container">
                <img src="../img/redteam.png" alt="赤チーム写真" class="team-photo">
                <span class="number">9</span>
            </div>
            <div class="team-info">
                <button class="role-button red-button" data-team-id="1" data-role-id="1">オペレーター</button>
                <div id="operator-red"></div>
                <br>
                <button class="role-button red-button" data-team-id="1" data-role-id="2">アストロノーツ</button>
                <div id="astronaut-red"></div>
            </div>
        </div>
    
        <div class="info-box">
            <h1>ルール</h1>
            <h2>ゲームの基本設定</h2>
        <p>チーム分け：プレイヤーは2つのチーム（赤チームと青チーム）に分かれます。それぞれのチームには1人のオペレーターと1人のアストロノーツがいます。
        <br>
        <br>
        カード配置：25枚のカードが5x5のグリッドに配置されます。それぞれのカードには1つの単語が書かれています。
        <br>
        <br>
        パソコンの画面の大きさを選ぶ際、80%から100%の範囲が特におすすめで、ゲームを楽しむのに最適です。
        </p>
        <button id="startGame" style="display: none;">ゲームスタート</button>
        </div>
        <div class="team-box blue-team">
            <h2>青チーム</h2>
            <div class="photo-container">
                <img src="../img/blueteam.png" alt="青チーム写真" class="team-photo">
                <span class="number">8</span>
            </div>
            <div class="team-info">
                <button class="role-button blue-button" data-team-id="2" data-role-id="1">オペレーター</button>
                <div id="operator-blue"></div>
                <br>
                <button class="role-button blue-button" data-team-id="2" data-role-id="2">アストロノーツ</button>
                <div id="astronaut-blue"></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
