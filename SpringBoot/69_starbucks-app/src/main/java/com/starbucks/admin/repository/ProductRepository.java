/*
 * 商品リポジトリインターフェース
 *
 * 商品情報のCRUD操作と、以下の機能を提供する：
 * - 商品の注文参照状態の確認
 * - 販売可能な商品の集計
 * - 最新商品の取得
 */
package com.starbucks.admin.repository;

import com.starbucks.admin.entity.Product;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import java.util.List;

public interface ProductRepository extends CrudRepository<Product, Integer> {

    /*
     * 指定された商品IDが注文テーブルで参照されているかを確認する
     *
     * 処理の流れ：
     * [1] orders テーブルから product_id の存在確認
     *
     * @param productId 確認対象の商品ID
     * @return 参照されている場合はtrue、そうでない場合はfalse
     */
    // TODO:ordersテーブルで指定したproduct_idが参照されているかを確認する
    @Query("SELECT COUNT(*) > 0 FROM orders WHERE product_id = :productId")
    boolean existsReferencedInOrder(Integer productId);

    /*
     * 販売可能な商品（is_available = true）の総数を取得する
     *
     * 処理の流れ：
     * [1] is_available が true の商品をカウント
     *
     * @return 販売可能な商品の総数
     */
    // TODO:販売中の商品
    @Query("SELECT COUNT(*) FROM products WHERE is_available = TRUE")
    long countByIsAvailableTrue();

    /*
     * 最近追加された商品を最大4件取得する
     *
     * 処理の流れ：
     * [1] 商品を登録順（ID降順）で4件取得
     * [2] 取得した商品をID昇順で並び替え
     *
     * @return 最新の商品リスト（最大4件）
     */
    // TODO:最近追加された商品
    @Query("""
           SELECT *
           FROM (
                SELECT * FROM products ORDER BY id DESC LIMIT 4
           ) sub
           ORDER BY id ASC
           """)
    List<Product> findLatestProduct();
}