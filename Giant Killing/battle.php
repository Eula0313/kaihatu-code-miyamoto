<?php
session_start();
require 'db-connect.php';

// 戦闘データを取得
$battle_data = &$_SESSION['battle_data'];

// ターン数初期化（初回アクセス時）
if (!isset($battle_data['turn_count'])) {
    $battle_data['turn_count'] = 1;
}

// 現在の先頭キャラクターを取得
$player_front = &$battle_data['player_team'][$battle_data['player_front']];
$enemy_front = &$battle_data['enemy_team'][$battle_data['enemy_front']];

// 初回ターンで自動的に先行キャラクターを判定して攻撃を行う
if ($battle_data['turn_count'] === 1 && !isset($battle_data['initialized'])) {
    $battle_data['logs'][] = "=== ターン {$battle_data['turn_count']} ===";

    if ($player_front['speed'] >= $enemy_front['speed']) {
        $battle_data['logs'][] = "プレイヤーが先攻！";
        
    } else {
        $battle_data['logs'][] = "敵が先攻！";
       
    }

    $battle_data['initialized'] = true;
    $_SESSION['battle_data'] = $battle_data;
}

// プレイヤーの行動処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $battle_data['logs'][] = "=== ターン {$battle_data['turn_count']} ===";

    // 通常攻撃
    if ($_POST['action'] === 'attack') {
        handleAttack($player_front, $enemy_front, $battle_data);
    }

    // スキル使用
    elseif ($_POST['action'] === 'skill' && isset($_POST['skill_id'])) {
        $skill_id = (int)$_POST['skill_id'];
        $selected_skill = findSkillById($player_front['skills'], $skill_id);
        if ($selected_skill) {
            handleSkill($player_front, $enemy_front, $selected_skill, $battle_data);
            // 敵が生存している場合に反撃
            if ($enemy_front['HP'] > 0) {
                enemyAttack($player_front, $enemy_front, $battle_data);
            }
        }
    }

    // バトル進行チェック
    checkBattleStatus($battle_data);

    // ターン終了後に次のターンに移行
    $battle_data['turn_count']++;
    $_SESSION['battle_data'] = $battle_data;

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// スキル検索関数
function findSkillById($skills, $skill_id) {
    foreach ($skills as $skill) {
        if ($skill['skill_id'] === $skill_id) {
            return $skill;
        }
    }
    return null;
}

// 通常攻撃処理
function handleAttack(&$attacker, &$defender, &$battle_data) {
    if ($attacker['speed'] >= $defender['speed']) {
        performAttack($attacker, $defender, $battle_data);
        if ($defender['HP'] > 0) {
            enemyAttack($attacker, $defender, $battle_data);
        }
    } else {
        enemyAttack($attacker, $defender, $battle_data);
        if ($attacker['HP'] > 0) {
            performAttack($attacker, $defender, $battle_data);
        }
    }
}

// スキル処理関数
function handleSkill(&$user, &$target, $skill, &$battle_data) {
    $battle_data['logs'][] = "{$user['name']} は特技「{$skill['name']} 」を使用した！";

    switch ($skill['type']) {
        case 'speed':
            if ($skill['name'] === '影走り') {
                $user['evade_next'] = true; // 次ターンの回避フラグ
                $battle_data['logs'][] = "{$user['name']} は影走りで次の攻撃を回避する準備をした！";
            } elseif ($skill['name'] === '先制攻撃') {
                if ($target['speed'] > $user['speed']) {
                    $user['speed'] = $target['speed'] + 1; // 一時的に速度を上げる
                    $battle_data['logs'][] = "{$user['name']} は次のターンで必ず先制攻撃を行う準備をした！";
                }
                performAttack($user, $target, $battle_data); // 即座に通常攻撃
            } elseif ($skill['name'] === 'すり抜け') {
                $battle_data['logs'][] = "{$user['name']} は防御を無視する攻撃を仕掛けた！";
                performAttack($user, $target, $battle_data);
            }
            break;
        case 'attack':
            $damage = $skill['power'];
            $target['HP'] = max(0, $target['HP'] - $damage);
            $battle_data['logs'][] = "{$target['name']} に {$damage} のダメージ！";

            // 敵が倒れた場合の処理
            if ($target['HP'] <= 0) {
                $battle_data['logs'][] = "{$target['name']} は倒れた！";
                if ($target === $battle_data['enemy_team'][$battle_data['enemy_front']]) {
                    $battle_data['enemy_front']++;
                }
            }
            break;

        case 'heal':
        case 'hp':
            if ($skill['name'] === '自己修復') {
                $heal = min($skill['power'], $user['HP_max'] - $user['HP']);
                $user['HP'] += $heal;
                $battle_data['logs'][] = "{$user['name']} は自己修復で {$heal} HP を回復した！";
            } elseif ($skill['name'] === '鉄壁の守り') {
                $user['defense_boost'] = 2; // 次の2ターン間ダメージ半減
                $battle_data['logs'][] = "{$user['name']} は鉄壁の守りで受けるダメージを半減する！";
            } elseif ($skill['name'] === '反撃') {
                $user['counter_chance'] = 0.6; // 反撃率を60%に設定
                $battle_data['logs'][] = "{$user['name']} は反撃態勢に入った！";
            }
            break;
    }
}

// 攻撃実行処理
function performAttack(&$attacker, &$defender, &$battle_data) {
    $damage = rand(10, 20);

    // ダメージ軽減処理
    if (isset($defender['defense_boost']) && $defender['defense_boost'] > 0) {
        $damage = floor($damage / 2);
        $battle_data['logs'][] = "{$defender['name']} は鉄壁の守りでダメージが半減した！";
    }

    $defender['HP'] = max(0, $defender['HP'] - $damage);
    $battle_data['logs'][] = "{$attacker['name']} の攻撃！ {$defender['name']} に {$damage} ダメージ！";

    // 鉄壁の守りターン数減少
    if (isset($defender['defense_boost']) && $defender['defense_boost'] > 0) {
        $defender['defense_boost']--;
    }

    if ($defender['HP'] <= 0) {
        $battle_data['logs'][] = "{$defender['name']} は倒れた！";
        if ($defender === $battle_data['enemy_team'][$battle_data['enemy_front']]) {
            $battle_data['enemy_front']++;
        }
    }
}

// 敵の攻撃処理 (修正版)
function enemyAttack(&$player_front, &$enemy_front, &$battle_data) {
    // スキル使用または通常攻撃をランダムで選択
    $use_skill = rand(0, 1) === 1; // 50%の確率でスキルを使用

    if ($use_skill && !empty($enemy_front['skills'])) {
        // 敵のスキルをランダムで選択
        $skill = $enemy_front['skills'][array_rand($enemy_front['skills'])];

        // 必中スキルの判定
        $is_certain_hit = isset($skill['certain_hit']) && $skill['certain_hit'] === true;

        // スキルの成功率をチェック
        if (rand(1, 100) <= $skill['success_rate']) {
            $battle_data['logs'][] = "{$enemy_front['name']} は特技「{$skill['name']}」を使用した！";

            // 必中スキルの場合、回避を無視
            if (!$is_certain_hit && isset($player_front['evade_next']) && $player_front['evade_next'] === true) {
                $battle_data['logs'][] = "{$player_front['name']} は影走りで攻撃を回避した！";
                $player_front['evade_next'] = false; // 回避後にリセット
                return; // 攻撃終了
            }

            switch ($skill['type']) {
                case 'attack':
                    $damage = $skill['power'];
                    $player_front['HP'] = max(0, $player_front['HP'] - $damage);
                    $battle_data['logs'][] = "{$player_front['name']} に {$damage} ダメージ！";

                    // プレイヤーが倒れた場合の処理
                    if ($player_front['HP'] <= 0) {
                        $battle_data['logs'][] = "{$player_front['name']} は倒れた！";
                        $battle_data['player_front']++; // 次のキャラクターに切り替え
                        checkBattleStatus($battle_data); // バトル終了条件をチェック
                        return; // 処理を終了
                    }
                    break;

                case 'heal':
                    $heal = min($skill['power'], $enemy_front['HP_max'] - $enemy_front['HP']);
                    $enemy_front['HP'] += $heal;
                    $battle_data['logs'][] = "{$enemy_front['name']} は {$heal} HP を回復した！";
                    break;

                case 'speed':
                    // 速度アップなどの特殊効果
                    $enemy_front['speed'] += $skill['power'];
                    $battle_data['logs'][] = "{$enemy_front['name']} はスピードが {$skill['power']} 上昇した！";
                    break;
            }
        } else {
            $battle_data['logs'][] = "{$enemy_front['name']} の特技「{$skill['name']}」は失敗した！";
        }
        return; // スキルを使用した場合は終了
    }

    // 通常攻撃処理
    $damage = rand(10, 20);

    // 必中攻撃の判定
    if (!isset($enemy_front['certain_hit']) && isset($player_front['evade_next']) && $player_front['evade_next'] === true) {
        $battle_data['logs'][] = "{$player_front['name']} は影走りで攻撃を回避した！";
        $player_front['evade_next'] = false; // 回避後にリセット
        return; // 攻撃終了
    }

    // ダメージ軽減処理 (鉄壁の守り)
    if (isset($player_front['defense_boost']) && $player_front['defense_boost'] > 0) {
        $damage = floor($damage / 2);
        $battle_data['logs'][] = "{$player_front['name']} は鉄壁の守りでダメージが半減した！";
    }

    // プレイヤーにダメージ適用
    $player_front['HP'] = max(0, $player_front['HP'] - $damage);
    $battle_data['logs'][] = "{$enemy_front['name']} の攻撃！ {$player_front['name']} に {$damage} ダメージ！";

    // プレイヤーが倒れた場合
    if ($player_front['HP'] <= 0) {
        $battle_data['logs'][] = "{$player_front['name']} は倒れた！";
        $battle_data['player_front']++; // 次のキャラクターに切り替え
        checkBattleStatus($battle_data); // バトル終了条件をチェック
        return; // 処理を終了
    }

    // 反撃処理
    if (isset($player_front['counter_chance']) && rand(1, 100) <= $player_front['counter_chance'] * 100) {
        $counter_damage = floor($damage * 0.6); // ダメージの60%を返す
        $enemy_front['HP'] = max(0, $enemy_front['HP'] - $counter_damage); // 敵にダメージを適用
        $battle_data['logs'][] = "{$player_front['name']} の反撃！ {$enemy_front['name']} に {$counter_damage} ダメージ！";

        // 敵が倒れた場合の処理
        if ($enemy_front['HP'] <= 0) {
            $battle_data['logs'][] = "{$enemy_front['name']} は倒れた！";
            $battle_data['enemy_front']++;
        }
    }
}

// バトル終了条件の確認
function checkBattleStatus(&$battle_data) {
    if ($battle_data['enemy_front'] >= count($battle_data['enemy_team'])) {
        header("Location: result.php?result=win");
        exit;
    } elseif ($battle_data['player_front'] >= count($battle_data['player_team'])) {
        header("Location: result.php?result=lose");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>バトル画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #2c3e50, #34495e);
            color: #ecf0f1;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #f1c40f;
            text-align: center;
            margin: 20px 0;
            text-shadow: 2px 2px 4px #000;
        }
        .battle-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            gap: 20px;
            position: relative; /* 必須: エフェクトの基準位置 */
            overflow: visible; /* エフェクトが親ボックス外に出るのを許可 */
        }
        .character-box {
            position: relative; /* 必須: エフェクトの基準位置 */
            overflow: visible; /* エフェクトが親ボックス外に出るのを許可 */
            position: relative;
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            width: 30%;
            position: relative;
        }
        .character-box img {
            
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid #ecf0f1;
        }
        .character-box p, .character-box h2 {
            margin: 10px 0;
            font-size: 16px;
        }
        .hp-bar-container {
            margin-top: 5px;
            width: 100%;
        }
        .hp-bar {
            background: #ecf0f1;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            overflow: hidden;
            height: 15px;
        }
        .hp-bar-fill {
    height: 100%;
    background: linear-gradient(to right, #e74c3c, #ff4500);
    transition: width 0.5s ease; /* 幅が0.5秒で変化 */
    box-shadow: 0 0 5px rgba(255, 69, 0, 0.7);
}
.hp-bar-fill.low {
    background: red;
}
.hp-bar-fill.medium {
    background: yellow;
}

        .log-box {
            margin: 0 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            width: 30%;
            height: 300px;
            overflow-y: auto;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.5);
            font-size: 14px;
            text-align: left;
        }
        .log-title {
            font-size: 16px;
            color: #f1c40f;
            margin-bottom: 10px;
        }
        .form-container {
            margin: 20px auto;
            padding: 10px;
            max-width: 600px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .button-group, .skill-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        button {
            width: 120px;
            padding: 10px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
        .effect {
    position: absolute;
    top: -100%; /* キャラクターの中心を基準 */
    left: 0%;
    transform: translate(-0%, -0%); /* 中心揃え */
    font-size: 24px;
    font-weight: bold;
    color: #e74c3c;
    opacity: 0;
    animation: fadeEffect 1s ease forwards;
    pointer-events: none; /* クリックを無効化 */
}

@keyframes fadeEffect {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.2);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.8);
    }
}

        button {
    position: relative; /* ツールチップ表示に必要 */
}

.tooltip {
    display: none;
    position: absolute;
    top: 100%; /* ボタンの下に表示 */
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 8px;
    border-radius: 5px;
    font-size: 14px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    white-space: nowrap;
    z-index: 100;
}

button:hover .tooltip {
    display: block; /* ホバー時にツールチップを表示 */
}
/* 基本エフェクト */
.effect-around {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 520px;
    height: 520px;
    border-radius: 50%;
    animation: pulseEffect 1s ease-out forwards;
    pointer-events: none;
    z-index: 10;
}
.effect-around {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(230, 255, 6, 0.8) 30%, transparent 70%);
    border-radius: 50%;
    animation: sparkEffect 1s ease-out forwards;
    pointer-events: none;
    z-index: 10;
}
/* ベースエフェクト */
.effect-around {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1); /* 初期サイズ */
    width: 120px; /* エフェクトの幅 */
    height: 120px; /* エフェクトの高さ */
    border-radius: 50%; /* 円形にする */
    border: 3px solid rgba(255, 69, 0, 0.7); /* 色を指定 */
    animation: rotateAndPulse 1s ease-out forwards; /* 回転+拡大のアニメーション */
    pointer-events: none; /* クリック不可にする */
    z-index: 10;
}

/* 回転と拡大を組み合わせたアニメーション */
@keyframes rotateAndPulse {
    0% {
        transform: translate(-50%, -50%) scale(0.5) rotate(0deg); /* 小さい状態で回転なし */
        opacity: 1; /* 完全に表示 */
    }
    50% {
        transform: translate(-50%, -50%) scale(1.5) rotate(180deg); /* 少し拡大し回転中 */
        opacity: 0.8;
    }
    100% {
        transform: translate(-50%, -50%) scale(2.5) rotate(360deg); /* 最大サイズで1回転完了 */
        opacity: 0; /* 消える */
    }
}

/* パーティクル（光の粒）エフェクト */
.particle {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 0, 0.9); /* 明るい黄色の粒 */
    border-radius: 50%;
    animation: particleMove 1s ease-out forwards;
    pointer-events: none;
}

/* パーティクルの移動アニメーション */
@keyframes particleMove {
    0% {
        transform: translate(0, 0) scale(1); /* 中央に配置 */
        opacity: 1;
    }
    100% {
        transform: translate(calc(100px * var(--dx)), calc(100px * var(--dy))) scale(0.5); /* ランダム方向に移動 */
        opacity: 0;
    }
}

.effect-around {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 0, 0.8) 50%, transparent 70%);
    border-radius: 50%;
    animation: radiateEffect 1s ease-out forwards;
    pointer-events: none;
    z-index: 10;
}

@keyframes radiateEffect {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(0.5);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(1.5);
    }
}

@keyframes sparkEffect {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(0.8);
    }
    50% {
        opacity: 0.7;
        transform: translate(-50%, -50%) scale(1.2);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(1.5);
    }
}

/* 技の種類に応じた色 */
.effect-around.kougeki {
    background: radial-gradient(circle, rgba(255, 69, 0, 0.8) 30%, transparent 70%);
}
.effect-around.fire {
    border: 3px solid rgba(255, 0, 230, 0.7); /* 赤色 */
}

.effect-around.water {
    border: 3px solid rgba(0, 128, 255, 0.7); /* 青色 */
}

.effect-around.grass {
    border: 3px solid rgba(0, 255, 0, 0.7); /* 緑色 */
}

/* エフェクトのアニメーション */
@keyframes pulseEffect {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(0.5);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(2);
    }
}
@keyframes characterShake {
    0% { transform: translate(0, 0); }
    25% { transform: translate(-10px, 0); }
    50% { transform: translate(10px, 0); }
    75% { transform: translate(-10px, 0); }
    100% { transform: translate(0, 0); }
}

.shake {
    animation: characterShake 0.5s ease; /* 0.5秒間揺れる */
}
/* キャラクターが拡大するアニメーション */
@keyframes characterScale {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.scale {
    animation: characterScale 0.5s ease; /* 0.5秒間拡大縮小 */
}@keyframes flyOff {
    0% {
        transform: translate(0, 0) scale(1); /* 初期位置 */
        opacity: 1;
    }
    50% {
        transform: translate(100px, -50px) scale(1.2); /* 中間地点で少し拡大 */
        opacity: 0.8;
    }
    100% {
        transform: translate(300px, -200px) scale(0.5); /* 画面外に飛ぶ */
        opacity: 0; /* 消える */
    }
}

.fly-off {
    animation: flyOff 1s ease forwards; /* 1秒間でアニメーション実行 */
    pointer-events: none; /* クリック無効化 */
}

    </style>
</head>
<body>
<iframe src="btlbgm_player.php" style="display:none;" id="bgm-frame"></iframe>

<h1>バトル画面 - ターン <?= htmlspecialchars($battle_data['turn_count']) ?></h1>
<!-- <h1>バトル画面 - ターン <?= htmlspecialchars($battle_data['turn_count'] ?? '1') ?></h1> -->


<div class="battle-container">
    <!-- プレイヤー情報 -->
    <div class="character-box" id="player-box">
        <img src="<?= htmlspecialchars($player_front['character_image'] ?? 'default_player.png') ?>" alt="<?= htmlspecialchars($player_front['name']) ?>">
        <h2>プレイヤー</h2>
        <p><strong><?= htmlspecialchars($player_front['name']) ?></strong></p>
        <div class="hp-bar-container">
            <div class="hp-bar">
                <div class="hp-bar-fill" style="width: <?= ($player_front['HP'] / $player_front['HP_max']) * 100 ?>%;"></div>
            </div>
        </div>
        <p>HP: <?= htmlspecialchars($player_front['HP']) ?>/<?= htmlspecialchars($player_front['HP_max']) ?></p>
        <p>スピード: <?= htmlspecialchars($player_front['speed']) ?></p>
    </div>

    <!-- ログボックス -->
    <div class="log-box">
        <div class="log-title">ターン <?= htmlspecialchars($battle_data['turn_count']) ?> のログ</div>
        <?php foreach (array_reverse($battle_data['logs']) as $log): ?>
            <p><?= htmlspecialchars($log) ?></p>
        <?php endforeach; ?>
    </div>

     <!-- 敵情報 -->
     <div class="character-box" id="enemy-box">
        <img src="<?= htmlspecialchars($enemy_front['character_image'] ?? 'default_enemy.png') ?>" alt="<?= htmlspecialchars($enemy_front['name']) ?>">
        <h2>敵</h2>
        <p><strong><?= htmlspecialchars($enemy_front['name']) ?></strong></p>
        <div class="hp-bar-container">
            <div class="hp-bar">
                <div class="hp-bar-fill" style="width: <?= ($enemy_front['HP'] / $enemy_front['HP_max']) * 100 ?>%;"></div>
            </div>
        </div>
        <p>HP: <?= htmlspecialchars($enemy_front['HP']) ?>/<?= htmlspecialchars($enemy_front['HP_max']) ?></p>
        <p>スピード: <?= htmlspecialchars($enemy_front['speed']) ?></p>
    </div>
</div>
<div class="form-container">
    <form method="POST">
        <div class="button-group">
            <button name="action" value="attack">通常攻撃</button>
        </div>
        <div class="skill-buttons">
            <?php foreach ($player_front['skills'] as $skill): ?>
                <button name="action" value="skill" onclick="this.form.skill_id.value='<?= htmlspecialchars($skill['skill_id']) ?>';" style="position: relative;">
                    <?= htmlspecialchars($skill['name']) ?>
                    <span class="tooltip">
                        効果: <?= htmlspecialchars($skill['effect']) ?><br>
            
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
        <input type="hidden" name="skill_id">
    </form>
</div>


<script>
   function animateCharacterWithSkill(targetId, skillType) {
    const target = document.getElementById(targetId);

    // 揺れるクラスを追加
    target.classList.add('shake');

    // 周囲のエフェクトを追加
    const effect = document.createElement('div');
    effect.className = `effect-around ${skillType}`; // 技タイプごとにクラスを追加
    target.style.position = 'relative'; // 親要素を基準にエフェクトを表示
    target.appendChild(effect);

    // アニメーションが終わった後にクラスとエフェクトを削除
    setTimeout(() => {
        target.classList.remove('shake');
        target.removeChild(effect);
    }, 1000); // 揺れとエフェクトの時間に合わせる
}
function animateCharacter(targetId, animationType) {
    const target = document.getElementById(targetId);

    // アニメーションの種類に応じてクラスを追加
    target.classList.add(animationType);

    // アニメーション終了後にクラスを削除
    setTimeout(() => {
        target.classList.remove(animationType);
    }, 500); // アニメーション時間と一致
}

// 通常攻撃ボタンがクリックされたときのイベント
document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
    animateCharacter('enemy-box', 'shake'); // 敵キャラを揺らす
});

// スキルボタンがクリックされたときのイベント
document.querySelectorAll('button[name="action"][value="skill"]').forEach(button => {
    button.addEventListener('click', () => {
        animateCharacter('player-box', 'scale'); // プレイヤーキャラを拡大
    });
});

// 攻撃ボタンがクリックされたときのイベント
document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
    animateCharacterWithSkill('enemy-box', 'kougeki'); // 技タイプを指定
});

// スキルボタンがクリックされたときのイベント
document.querySelectorAll('button[name="action"][value="skill"]').forEach((button, index) => {
    button.addEventListener('click', () => {
        const skillType = ['fire', 'water', 'grass'][index % 3]; // 技タイプを模擬的に選択
        animateCharacterWithSkill('player-box', skillType); // 技タイプを指定
    });
});

function animateCharacterWithSkill(targetId, skillType) {
    const target = document.getElementById(targetId);

    // 揺れるクラスを追加
   

    // 周囲のエフェクトを追加
    const effect = document.createElement('div');
    effect.className = `effect-around ${skillType}`; // 技タイプごとに色を変えるクラス
    target.style.position = 'relative'; // 親要素を基準にエフェクトを表示
    target.appendChild(effect);

    // パーティクルを追加
    for (let i = 0; i < 10; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.setProperty('--dx', Math.random() * 2 - 1); // ランダムなX方向
        particle.style.setProperty('--dy', Math.random() * 2 - 1); // ランダムなY方向
        target.appendChild(particle);

        // 一定時間後にパーティクルを削除
        setTimeout(() => {
            target.removeChild(particle);
        }, 1000);
    }

    // アニメーションが終わった後にクラスとエフェクトを削除
    setTimeout(() => {
        target.classList.remove('shake');
        target.removeChild(effect);
    }, 1000); // 揺れとエフェクトの時間に合わせる
}

// 攻撃ボタンがクリックされたときのイベント
document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
    animateCharacterWithSkill('enemy-box', 'kougeki'); // 技タイプを指定
});

// スキルボタンがクリックされたときのイベント
document.querySelectorAll('button[name="action"][value="skill"]').forEach((button, index) => {
    button.addEventListener('click', () => {
        const skillType = ['fire', 'water', 'grass'][index % 3]; // 技タイプを模擬的に選択
        animateCharacterWithSkill('player-box', skillType); // 技タイプを指定
    });
});


     
    // 攻撃ボタンがクリックされたときのイベント
    document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
        showAroundEffect('enemy-box'); // 敵キャラの周りにエフェクト
    });

    // スキルボタンがクリックされたときのイベント
    document.querySelectorAll('button[name="action"][value="skill"]').forEach(button => {
        button.addEventListener('click', () => {
            showAroundEffect('player-box'); // プレイヤーキャラの周りにエフェクト
        });
    });
    // 攻撃エフェクト表示
    function showEffect(targetId, text) {
        const target = document.getElementById(targetId);
        const effect = document.createElement('div');
        effect.className = 'effect';
        effect.innerText = text;
        target.appendChild(effect);

        // エフェクトを一定時間後に削除
        setTimeout(() => {
            target.removeChild(effect);
        }, 1000);
    }

    // 通常攻撃時に敵のところにエフェクトを表示
    document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
        const damage = Math.floor(Math.random() * 20) + 10; // ダメージをランダム生成
        showEffect('enemy-box', `攻撃 -${damage} `);
    });
    // スキル使用時に自分のところにエフェクトを表示
    document.querySelectorAll('button[name="action"][value="skill"]').forEach(button => {
        button.addEventListener('click', () => {
            showEffect('player-box', 'スキル発動！');
        });
    });
    function updateHpBar(targetId, currentHp, maxHp) {
    const target = document.getElementById(targetId);
    const hpBarFill = target.querySelector('.hp-bar-fill');

    // HPバーの幅を計算して設定
    const hpPercentage = (currentHp / maxHp) * 100;
    hpBarFill.style.width = `${hpPercentage}%`;
}

// ダメージを受けたときのサンプル
document.querySelector('button[name="action"][value="attack"]').addEventListener('click', () => {
    const newHp = Math.max(0, enemyCurrentHp - 20); // 20ダメージ
    enemyCurrentHp = newHp; // 現在のHPを更新
    updateHpBar('enemy-box', newHp, enemyMaxHp); // HPバーを更新
});

// スキルで回復したときのサンプル
document.querySelectorAll('button[name="action"][value="skill"]').forEach(button => {
    button.addEventListener('click', () => {
        const newHp = Math.min(playerMaxHp, playerCurrentHp + 15); // 15回復
        playerCurrentHp = newHp; // 現在のHPを更新
        updateHpBar('player-box', newHp, playerMaxHp); // HPバーを更新
    });
});

// 初期HP設定（例）
let enemyCurrentHp = 100;
const enemyMaxHp = 100;

let playerCurrentHp = 100;
const playerMaxHp = 100;

</script>

</body>
</html>
