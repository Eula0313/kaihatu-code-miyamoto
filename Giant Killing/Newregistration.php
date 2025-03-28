<?php
// register.php

require 'db-connect.php';

// セッションを開始
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    // バリデーション
    if (!empty($user_name) && !empty($password)) {
        // パスワードをハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // DBにユーザーを登録
            $sql = "INSERT INTO users (user_name, password) VALUES (:user_name, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // 登録したユーザーのIDを取得
            $user_id = $pdo->lastInsertId();

            // セッションにユーザーIDを保存
            $_SESSION['user_id'] = $user_id;

            // クッキーにユーザーIDを保存（例: 30日間有効）
            setcookie('user_id', $user_id, time() + (30 * 24 * 60 * 60), '/', '', false, true);

            // 図鑑データの挿入
            $zukan_sql = "
                INSERT INTO zukan (character_id, user_id, character_image, character_description)
                VALUES
                (1, :user_id, 'image/☆１寝顔.png', '寝ることが得意。気が付いたら作業から目を背けている'),
                (2, :user_id, 'image/☆１シンプル南.gif', '無口でシンプルな性格だが、心には深い情熱を秘めている...'),
                (3, :user_id, 'image/☆１ダークサイド.png', 'なぜ闇落ちしたのかは不明。必殺技はドラゴブラスター'),
                (4, :user_id, 'image/☆１南バズ.png', '南の地から来た冒険者。楽観的な性格で、いつも元気な声で周囲を盛り上げる'),
                (5, :user_id, 'image/☆１頭.gif', 'その頭の大きさに反して、抜群のバランス感覚を持つ。知識にあふれ、パズルや戦術的な戦いを得意とする'),
                (6, :user_id, 'image/☆２全身タイツ.png', '全身をピンクのタイツで覆い、素早い動きを得意とする。見た目のインパクトとは裏腹に、かなりのスピードを...'),
                (7, :user_id, 'image/☆２banana.gif', '常にバナナを持ち歩いており、健康志向。敵を滑らせることが得意で、トリッキーな戦い方を好む'),
                (8, :user_id, 'image/☆２ハサウェイ.gif', '宮イダーマンのライバル的存在'),
                (9, :user_id, 'image/☆２藤ンダカー.png', '呪物'),
                (10, :user_id, 'image/☆２彼女目線.png', '常に彼女目線で物事を捉える、不思議なキャラクター。敵にも思いやりを見せることがあり、その優しさが武器...'),
                (11, :user_id, 'image/☆３ちぴちゃぱ.gif', '小柄でかわいらしい外見ながら、意外とタフな性格。軽やかな身のこなしで敵を翻弄する'),
                (12, :user_id, 'image/☆３南オラフ.png', '雪の国から来た謎のキャラクター。寒さには強いが、炎には弱いという弱点がある'),
                (13, :user_id, 'image/☆４yagi.gif', 'ヤギのような強靭な精神力を持ち、どんな困難も乗り越える。角を使った突進攻撃が得意'),
                (14, :user_id, 'image/☆４宮ワトソン.png', '名探偵の助手でありながら、自身も優れた推理力を持つ。探偵スキルを駆使して敵の弱点を見つける'),
                (15, :user_id, 'image/☆４南ラッセル.png', '考古学者のような見た目で、古代の謎に興味がある。古代の力を使った攻撃が得意'),
                (16, :user_id, 'image/☆５フリー素材おじさん.png', 'どこにでもいるようなおじさんだが、実は強力な力を持つ隠れキャラ。どんな場面でも柔軟に対応する'),
                (17, :user_id, 'image/☆５宮イダーマン.png', '普段は身バレ防止のためマスクをかぶっている。正体はニューヨークに住む平凡な大学生'),
                (18, :user_id, 'image/☆５八木.png', '冷静かつ大胆な戦い方をするキャラクター。周囲の状況を見極め、最適な行動を取ることができる'),(19, :user_id, 'image/☆２南ジーニー.png', '神秘的な魔法のランプから召喚される、願いを叶える魔法使い。基本的には陽気で親切な性格だが、時には独特な方法で願いを叶えることもある。攻撃はすべて魔法を用いて行い、敵を幻惑するようなトリッキーな戦法を得意とする'),
                (20, :user_id, 'image/☆２ハートの女王らいや.png', '愛と情熱を司る女王。心を操る魔法を使い、仲間には優しく、敵には厳しい一面を持つ。彼女の魔法は、味方の回復や敵の弱体化を得意とし、特に心に響く言葉で戦局を左右する'),
                (21, :user_id, 'image/☆１内山.png', '地道な努力を重ねる勤勉なキャラクター。控えめな性格だが、困ったときには頼れる存在で、何事もコツコツと積み上げるタイプ。得意技は「根気の一撃」、一見地味だが確実に効果を発揮する堅実な攻撃を繰り出す'),
                (22, :user_id, 'image/☆３細川.png', '筋トレを愛するストイックなキャラクター。毎日欠かさずトレーニングを行い、強靭な筋肉と不屈の精神を持つ。得意技は「パワースマッシュ」、その鍛え上げられた腕力で敵を圧倒する。筋肉への情熱が周囲にも影響を与え、仲間を励ます力強い存在だ'),
                (23, :user_id, 'image/☆１ひょろがり藤川.png', '藤川の細すぎるバージョン。何をしても全力なのだが、体力が足りずすぐに息切れしてしまう。普段は食べ物を探しながらふらふらしているが、戦闘中には自分より弱そうな相手を見つけると勢いよく挑む'),
                (24, :user_id, 'image/☆２ぱちもん藤川.png', 'どこかで見たことがあるような、けれど微妙に違う「藤川」の姿。その正体は、他人が真似して作った偽物の藤川。「ぱちもん」として扱われるのを本人（？）は気にしていない様子'),
                (25, :user_id, 'image/☆１角シンドローム.png', '常に頭に謎の「角（つの）」が生えている不思議なキャラクター。興奮すると角が巨大化し、暴走状態になるが、冷静さを失うため制御が難しい'),
                (26, :user_id, 'image/☆２ウッディ南.png', '木目調の体を持つキャラクターで、まるで木製のおもちゃのような外見。「自然の守護者」を自称しており、いつも仲間に助言をしてくれる頼れる存在。しかしその見た目から「人形扱い」されるのが大嫌いで、それを指摘されると少し機嫌を損ねる'),
                (27, :user_id, 'image/☆１さぼりオオトソイ.png', '巨大な鳥居を背負いながらも、いつもやる気がなくサボることばかり考えているキャラクター。見た目は威厳があり、鳥居の角が光ると「神の力」を発揮するという噂があるが、実際にはめったに働かない')

            ";
            $zukan_stmt = $pdo->prepare($zukan_sql);
            $zukan_stmt->bindParam(':user_id', $user_id);
            $zukan_stmt->execute();


             // itemsテーブルの挿入
            $item_sql = "
            INSERT INTO items (user_id, item_name, price, effect, item_image, level)
            VALUES
            (:user_id, 'レア薬', 1500, '出現するレア度を上げる', 'image/rea.png', 0),
            (:user_id, '栽培速度UP薬', 2500, '栽培速度が倍になる', 'image/kabi.png', 0),
            (:user_id, '生命維持装置', 2000, '自動消滅を防ぐことができる', 'image/seimei.png', 0),
            (:user_id, 'SD3Eワールド', 1000, 'SD3Eワールドをアンロック', 'image/SD3E.png', 0),
            (:user_id, 'ディズニーワールド', 1000, 'ディズニーワールドをアンロック', 'image/ディズニー.png', 0),
            (:user_id, '広告消去権', 2000, '広告が表示されなくなる', 'image/koukoku.png', 0)
            ";
            $item_stmt = $pdo->prepare($item_sql);
            $item_stmt->bindParam(':user_id', $user_id);
            $item_stmt->execute();

            // toolsテーブルの挿入
            $tools_sql = "
            INSERT INTO tools (user_id, tool_name, effect, price, tool_image, level, tool_id)
            VALUES
            (:user_id, '照明器', 'レア度上昇', 250, 'image/shoumei.png', 1 , 1),
            (:user_id, '加湿器', '発生速度', 200, 'image/kasitu.png', 1 , 2),
            (:user_id, '保温器', '枯れにくさ', 200, 'image/hoon.png', 1 , 3)
            ";
            $tools_stmt = $pdo->prepare($tools_sql);
            $tools_stmt->bindParam(':user_id', $user_id);
            $tools_stmt->execute();


         
            // 登録完了後 menu.php へリダイレクト
            header('Location: menu.php');
            exit;
        } catch (PDOException $e) {
            // エラーメッセージ
            $error_message = '登録中にエラーが発生しました。';
        }
    } else {
        $error_message = '全てのフィールドを入力してください。';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <style>
        /* リセットスタイル */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: url('image/toroku.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
        }

        .container {
            background: rgba(49 47 47 / 80%); /* 背景を濃く調整 */
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 16px; /* フォントサイズを調整 */
            color: #ffffff;
            margin-bottom: 5px;
            align-self: flex-start;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ffffff; /* 白い枠線を追加 */
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2); /* 半透明の白背景 */
            font-size: 16px;
            color: #ffffff;
            transition: background 0.3s, box-shadow 0.3s;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #dddddd; /* プレースホルダーを薄い灰色に */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            background: rgba(255, 255, 255, 0.3); /* フォーカス時の背景を少し明るく */
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(135deg, #0056b3, #004494); /* 濃い青に変更 */
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #004494, #003377); /* ホバー時にさらに濃く */
            box-shadow: 0 5px 15px rgba(0, 51, 119, 0.4);
        }

        .back-button {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid #ffffff;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: #ffffff;
            color: #000;
        }

        p {
            font-size: 14px;
            color: #ff6b6b; /* エラーメッセージを目立たせる */
            margin-top: 10px;
        }
    </style>
</head>
<body>
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
    <div class="container">
        <h1>新規登録</h1>
        <form method="post">
            <label for="user_name">ユーザー名</label>
            <input type="text" name="user_name" id="user_name" placeholder="ユーザー名を入力" required>
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" placeholder="パスワードを入力" required>
            <?php if (isset($error_message)) { echo '<p>' . htmlspecialchars($error_message) . '</p>'; } ?>
            <button type="submit">登録</button>
        </form>
        <form action="menu.php">
            <button type="button" class="back-button" onclick="window.location.href='menu.php';">戻る</button>
        </form>
    </div>
   
</body>
</html>
