<?php
session_start();
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';
$role_id = $_SESSION['role_id'] ?? '';
$team_id = $_SESSION['team_id'] ?? '';

if (empty($room_id) || empty($role_id) || empty($team_id)) {
    echo 'Room ID, role ID, or team ID is missing';
    exit();
}

$_SESSION['room_id'] = $room_id;

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM Board WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $board = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$board) {
        throw new Exception('Board data not found');
    }

    $stmt = $pdo->prepare("SELECT color, COUNT(*) as count FROM Board WHERE room_ID = ? AND state_ID = 2 GROUP BY color");
    $stmt->execute([$room_id]);
    $color_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $counts = [
        'red' => 0,
        'blue' => 0,
        'black' => 0,
        'white' => 0
    ];

    foreach ($color_counts as $count) {
        $counts[$count['color']] = $count['count'];
    }

    $red_count = $counts['red'];
    $blue_count = $counts['blue'];

    $is_astronaut = ($role_id == 2);

    $stmt = $pdo->prepare("SELECT current_team, current_role, hint_text, hint_count FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $current_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current_state) {
        throw new Exception('Game state not found');
    }

    $current_team = $current_state['current_team'];
    $current_role = $current_state['current_role'];
    $hint_text = $current_state['hint_text'];
    $hint_count = $current_state['hint_count'];

    $is_current_turn = ($current_team == $team_id && $current_role == $role_id);
    $original_hint_count = $hint_count; // ここでオリジナルのヒント枚数を保持

    // ユーザー数を取得してセッションに保存
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $userCount = $stmt->fetchColumn();
    $_SESSION['user_count'] = $userCount; // セッションに参加人数を保存

    


} catch (Exception $e) {
    echo 'エラー: ' . $e->getMessage();
    exit();
}

function getRandomImage($color) {
    $images = [
        'red' => ['red.webp'],
        'blue' => ['blue.webp'],
        'black' => ['black.webp'],
        'white' => ['white.webp']
    ];
    $imageList = $images[$color] ?? ['default.webp'];
    return $imageList[array_rand($imageList)];
}

$stmt = $pdo->prepare("
    SELECT 
        u.team_ID, 
        u.role_ID, 
        l.hint, 
        l.sheet
    FROM 
        Log l 
        JOIN User u ON l.user_ID = u.user_ID 
    WHERE 
        l.room_ID = ? 
    ORDER BY 
        l.log_ID DESC
");
$stmt->execute([$room_id]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);


// データベースからユーザー情報を取得
$stmt = $pdo->prepare("SELECT user_name, team_ID, role_ID FROM User WHERE room_ID = ?");
$stmt->execute([$room_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 各チームと役割のユーザー名を格納する文字列変数の初期化
$red_operator_names_str = "";
$red_astronaut_names_str = "";
$blue_operator_names_str = "";
$blue_astronaut_names_str = "";

// ユーザーの名前を条件に応じて文字列変数に追加
foreach ($users as $user) {
    if ($user['team_ID'] == 1 && $user['role_ID'] == 1) { // 赤チームのオペレーター
        $red_operator_names_str .= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') . "<br>";
    } else if ($user['team_ID'] == 1 && $user['role_ID'] == 2) { // 赤チームのアストロノーツ
        $red_astronaut_names_str .= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') . "<br>";
    } else if ($user['team_ID'] == 2 && $user['role_ID'] == 1) { // 青チームのオペレーター
        $blue_operator_names_str .= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') . "<br>";
    } else if ($user['team_ID'] == 2 && $user['role_ID'] == 2) { // 青チームのアストロノーツ
        $blue_astronaut_names_str .= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') . "<br>";
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Anonymous Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Exo:400,700" rel="stylesheet">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
     $(document).ready(function() {
    let selectedCardId = null;
    let gameEnded = false;
    let isMyTurn = <?php echo json_encode($is_current_turn); ?>; // 自分のターンかどうかのフラグ

    function attachCardClickHandlers() {
        if (gameEnded) return; // ゲーム終了後は操作を無効にする
        $('.card').off('click').on('click', function() {
            <?php if ($is_current_turn && $role_id == 2): ?>
                selectedCardId = $(this).data('card-id');
                $('#overlay').addClass('active');
                $('#flip-popup').addClass('active');
            <?php else: ?>
                alert('あなたの役割ではカードをめくることはできません');
            <?php endif; ?>
        });
    }

    attachCardClickHandlers();

    $('#confirm-flip').click(function() {
        if (selectedCardId) {
            $.post('flip_card.php', {card_id: selectedCardId, room_id: '<?php echo $room_id; ?>'}, function(response) {
                if (response.status === 'success') {
                    updateBoard();
                    if (response.reload) {
                        location.reload(); // カードをめくった直後にページをリロード
                    }
                } else if (response.status === 'win') {
                    gameEnded = true;
                    if (response.message === '赤チームの勝ち！') {
                        $('#winred-message').text(response.message);
                        $('#flip-popup').hide();
                        $('#winred-popup').show();
                   } else {
                        $('#winblue-message').text(response.message);
                        $('#flip-popup').hide();              
                        $('#winblue-popup').show();
                   }
                $('#overlay').show(); // 勝利時のポップアップを表示
 
                } else {
                    alert(response.message);
                }
                $('#overlay').removeClass('active');
                $('#flip-popup').removeClass('active');
            }, 'json');
        }
    });

    $('#cancel-flip').click(function() {
        $('#overlay').removeClass('active');
        $('#flip-popup').removeClass('active');
    });

    $('#return-to-room-red').click(function() {
                window.location.href = '../G1-2/G1-2.php';
            });
 
            $('#return-to-room-blue').click(function() {
                window.location.href = '../G1-2/G1-2.php';
            });

    $('#hint-form').submit(function(e) {
        e.preventDefault();
        const hint = $('#hint').val();
        const hintCount = $('#hint-count').val();
        const errorMessage = $('#error-message');
        
        if (hint.length > 10) {
            errorMessage.text('ヒントは10文字以内で入力してください。').show();
        } else {
            errorMessage.hide();
            $.post('submit_hint.php', {room_id: '<?php echo $room_id; ?>', hint: hint, hint_count: hintCount}, function(response) {
                if (response.status === 'success') {
                    updateBoard();
                    location.reload(); // ヒントを送信した直後にページをリロード
                } else {
                    alert(response.message);
                }
            }, 'json');
        }
    });

    $('#end-turn').click(function() {
        $.post('end_turn.php', {room_id: '<?php echo $room_id; ?>'}, function(response) {
            if (response.status === 'success') {
                isMyTurn = false; // 自分のターンが終わる
                updateBoard();
                location.reload(); // 自身のターンが終わった直後にページをリロード
            } else {
                alert(response.message);
            }
        }, 'json');
    });

    function updateBoard() {
        if (isMyTurn) return; // 自分のターンなら更新をスキップ

        $.get('get_board.php', {room_id: '<?php echo $room_id; ?>'}, function(response) {
            console.log('Board response:', response); // デバッグログ追加
            if (response.status === 'success') {
                $('#game-board').html(response.board);
                $('#red-count').text(response.red_count);
                $('#blue-count').text(response.blue_count);
                attachCardClickHandlers();
            } else {
                console.error(response.message);
            }
        }, 'json');

        $.get('get_game_state.php', {room_id: '<?php echo $room_id; ?>'}, function(response) {
            console.log('Game state response:', response); // デバッグログ追加
            if (response.status === 'success') {
                // ターンが切り替わったらページをリロード
                if (!isMyTurn && response.game_state.current_team == <?php echo json_encode($team_id); ?> && response.game_state.current_role == <?php echo json_encode($role_id); ?>) {
                    location.reload();
                }
                // 現在のチーム・役割のUIを更新
                $('#current-team-role').text((response.game_state.current_team == 1 ? '赤' : '青') + 'チームの' + (response.game_state.current_role == 1 ? 'オペレーター' : 'アストロノーツ'));
                // ゲームのログを更新
                updateLog();
            } else {
                console.error(response.message);
            }
        }, 'json');
    }

    function updateLog() {
        $.get('get_log.php', {room_id: '<?php echo $room_id; ?>'}, function(response) {
            if (response.status === 'success') {
                let logHtml = '';                    
                response.logs.forEach(log => {
                    logHtml += '<tr><td>' + escapeHtml(log.team_name) + '</td><td>' + escapeHtml(log.hint) + '</td><td>' + escapeHtml(log.sheet) + '</td></tr>';
                });
                $('#log-table tbody').html(logHtml);
            } else {
                console.error(response.message);
            }
        }, 'json');
    }

    function escapeHtml(string) {
        return String(string).replace(/[&<>"'`=\/]/g, function (s) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;',
                '=': '&#x3D;',
                '`': '&#x60;'
            }[s];
        });
    }

    // 定期的に更新を確認する
    setInterval(updateBoard, 500);

    // 勝利チェックを定期的に行う
    setInterval(checkWin, 1000);

    function checkWin() {
        $.get('win_check.php', {room_id: '<?php echo $room_id; ?>'}, function(response) {
            if (response.status === 'win') {
                gameEnded = true;
                    if (response.message === '赤チームの勝ち！') {
                        $('#winred-message').text(response.message);
                        $('#flip-popup').hide();
                        $('#winred-popup').show();
                   } else {
                        $('#winblue-message').text(response.message);
                        $('#flip-popup').hide();              
                        $('#winblue-popup').show();
                   }
                $('#overlay').show(); // 勝利時のポップアップを表示
 
            }
        }, 'json');
    }
});

    </script>
</head>
<body>
            

    <div class="area">  
    <?php require '../header/header.php';?>

    <div class="counts">
            <div id="current-team-role"><?php echo ($current_team == 1 ? '赤' : '青') . 'チームの' . ($current_role == 1 ? 'オペレーター' : 'アストロノーツ'); ?></div>
            </div>
    <ul class="circles">
        <div class="container">
           <div class="team-box red-team">
                <div class="photo-container">
                    <img src="../img/redteam.png" alt="赤チーム写真" class="ateam-photo"></div>
                <div class="rno-counts"> 
                    <span id="red-count"><?php echo $red_count; ?></span></div>
                <div class="team-info">
                    <p>オペレーター</p>
                    <div class="namerep"><?php echo $red_operator_names_str; ?></div>
                    <br>
                    <p>アストロノーツ</p>
                    <div class="namerep"><?php echo $red_astronaut_names_str; ?></div>
                </div>
            </div>

            <div id="game-board">
                <?php
                $cards_per_row = 5;
                $total_cards = count($board);

                for ($i = 0; $i < $total_cards; $i += $cards_per_row) {
                    echo '<div class="row">';
                    for ($j = 0; $j < $cards_per_row; $j++) {
                        if ($i + $j < $total_cards) {
                            $card = $board[$i + $j];
                            if ($card['state_ID'] == 1) {
                                $background_image = getRandomImage($card['color']);
                                echo '<div class="card" data-card-id="' . $card['board_ID'] . '" style="background-image: url(../img/' . $background_image . ');"></div>';
                            } else {
                                $background_color = $is_astronaut ? 'gray' : $card['color'];
                                echo '<div class="card" data-card-id="' . $card['board_ID'] . '" style="background-color: ' . $background_color . ';">' . $card['card_name'] . '</div>';
                            }
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>

            <div class="team-box blue-team">
                <div class="photo-container">
                    <img src="../img/blueteam.png" alt="青チーム写真" class="bteam-photo"></div>
                <div class="bno-counts">  
                    <span id="blue-count"><?php echo $blue_count; ?></span></div>
                <div class="team-info">
                    <p>オペレーター</p>
                    <div class="namerep"><?php echo $blue_operator_names_str; ?></div>
                    <br>
                    <p>アストロノーツ</p>
                    <div class="namerep"><?php echo $blue_astronaut_names_str; ?></div>
                </div>
            </div>
        </div>
        <div class="hint">
    <?php if ($is_current_turn && $role_id == 1): ?>
        <div class="hint-input space-theme">
            <form id="hint-form">
                <label for="hint">ヒント:</label>
                <input type="text" id="hint" name="hint" class="space-input">
                <label for="hint-count">枚数:</label>
                <select id="hint-count" name="hint-count" class="space-select" required>
                    <?php for ($i = 1; $i <= 9; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="Button-style">送信</button>
                <p id="error-message" class="hint-error">ヒントは10文字以内で入力してください。</p>
            </form>
        </div>
    <?php elseif ($is_current_turn && $role_id == 2): ?>
        <div class="hint-display">
            <p>ヒント: <?php echo htmlspecialchars($hint_text); ?></p>
            <p>めくれる枚数: 残り<?php echo htmlspecialchars($original_hint_count + 1); ?>枚</p>
            <button id="end-turn" class="Button-style">推測終了</button>
        </div>
    <?php else: ?>
        <p>現在のターンではありません。待機してください。</p>
    <?php endif; ?>

    <img src="../img/earth.png" alt="宇宙遊泳" class="earth-image">


    <div id="overlay" class="overlay"></div>
    <div id="flip-popup" class="flippopup">
        <p>カードをめくりますか？</p>
        <button id="confirm-flip">はい</button>
        <button id="cancel-flip">いいえ</button>
    </div>

    <div class="winred-popup" id="winred-popup">
        <p id="winred-message"></p>
        <button id="return-to-room-red">ルーム作成に戻る</button>
    </div>

    <div class="winblue-popup" id="winblue-popup">
        <p id="winblue-message"></p>
        <button id="return-to-room-blue">ルーム作成に戻る</button>
    </div>

</div>

            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="log">
        <h3 style="text-align: center;">宇宙遊泳記録</h3>
            <table id="log-table">
                <thead>
                    <tr>
                        <th>チーム名</th>
                        <th>ヒント</th>
                        <th>数</th>
                    </tr>
                </thead>
                <tbody>
    <?php
        $stmt = $pdo->prepare("
            SELECT 
                t.team_name,  -- チーム名を取得
                l.hint, 
                l.sheet,
                u.team_ID  -- team_IDを取得
            FROM 
                Log l 
                JOIN User u ON l.user_ID = u.user_ID 
                JOIN Team t ON u.team_ID = t.team_ID 
            WHERE 
                l.room_ID = ? 
            ORDER BY 
                l.log_ID DESC
        ");
        $stmt->execute([$room_id]);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($logs as $log) {
            // チームIDに応じて色を設定
            $color = ($log['team_ID'] == 1) ? 'red' : 'blue';
            
            echo '<tr>';
            echo '<td style="color: ' . htmlspecialchars($color) . ';">' . htmlspecialchars($log['team_name']) . '</td>';
            echo '<td>' . htmlspecialchars($log['hint']) . '</td>';
            echo '<td>' . htmlspecialchars($log['sheet']) . '</td>';
            echo '</tr>';
        }
    ?>
</tbody>

        </div>
    </div> 
</body>
</html>
