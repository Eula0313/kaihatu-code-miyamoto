/*
 * オプションリポジトリインターフェース
 *
 * 商品オプション情報のCRUD操作と、以下の機能を提供する：
 * - オプションの注文参照状態の確認
 * - 有効なオプションの集計
 * - 最新オプションの取得
 */
package com.starbucks.admin.repository;

import com.starbucks.admin.entity.Option;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import java.util.List;

public interface OptionRepository extends CrudRepository<Option, Integer> {

    /*
     * 指定されたオプションIDが注文テーブルで参照されているかを確認する
     *
     * 処理の流れ：
     * [1] orders テーブルから option_id の存在確認
     *
     * @param optionId 確認対象のオプションID
     * @return 参照されている場合はtrue、そうでない場合はfalse
     */
    @Query("SELECT COUNT(*) > 0 FROM orders WHERE option_id= :optionId")
    boolean existsReferencedInOrder(Integer optionId);

    /*
     * 有効なオプション（is_available = true）の総数を取得する
     *
     * 処理の流れ：
     * [1] is_available が true のオプションをカウント
     *
     * @return 有効なオプションの総数
     */
    // TODO:有効オプション数
    @Query("SELECT COUNT(*) FROM options WHERE is_available = TRUE")
    long countByIsAvailableTrue();

    /*
     * 最近追加されたオプションを最大4件取得する
     *
     * 処理の流れ：
     * [1] オプションを登録順（ID降順）で4件取得
     * [2] 取得したオプションをID昇順で並び替え
     *
     * @return 最新のオプションリスト（最大4件）
     */
    // TODO:最近追加されたオプション
    @Query("""
           SELECT * 
           FROM (
                SELECT * FROM options ORDER BY id DESC LIMIT 4
           ) sub 
           ORDER BY id ASC
           """)
    List<Option> findLatestOption();
}