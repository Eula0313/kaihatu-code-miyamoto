<?php
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];

    // トランザクションの開始
    $pdo->beginTransaction();

    try {
        // 関連する掲示板IDを取得
        $stmt = $pdo->prepare('SELECT post_id FROM post WHERE cate_id = ?');
        $stmt->execute([$category_id]);
        $post_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($post_ids) {
            // `t_rireki` テーブルから関連するエントリを削除
            $in_query = implode(',', array_fill(0, count($post_ids), '?'));
            $stmt = $pdo->prepare("DELETE FROM t_rireki WHERE post_id IN ($in_query)");
            $stmt->execute($post_ids);

            // `post` テーブルから関連する掲示板を削除
            $stmt = $pdo->prepare("DELETE FROM post WHERE cate_id = ?");
            $stmt->execute([$category_id]);
        }

        // `category` テーブルからカテゴリーを削除
        $stmt = $pdo->prepare("DELETE FROM category WHERE cate_id = ?");
        $stmt->execute([$category_id]);

        // コミット
        $pdo->commit();

        // 削除後のリダイレクト
        header("Location: category_show.php?delete_success=1");
        exit();
    } catch (Exception $e) {
        // ロールバック
        $pdo->rollBack();
        echo "エラーが発生しました: " . $e->getMessage();
    }
}
