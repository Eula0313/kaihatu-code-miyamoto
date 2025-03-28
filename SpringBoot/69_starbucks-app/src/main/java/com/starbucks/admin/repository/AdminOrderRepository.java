/*
 * 管理者用注文リポジトリインターフェース
 *
 * 注文情報のCRUD操作と、以下の集計機能を提供する：
 * - 月次売上集計
 * - 商品別売上ランキング
 * - 時間帯別注文数集計
 * - ステータス別注文数集計
 */
package com.starbucks.admin.repository;

import com.starbucks.admin.dto.MonthlySalesDTO;
import com.starbucks.admin.dto.ProductSalesDTO;
import com.starbucks.admin.dto.StatusOrdersDTO;
import com.starbucks.admin.dto.TimeSlotOrdersDTO;
import com.starbucks.admin.entity.Order;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import java.util.List;

public interface AdminOrderRepository extends CrudRepository<Order, Long> {

    /*
     * 月別の売上合計を集計する
     *
     * 処理の流れ：
     * [1] 注文日を年月でグループ化
     * [2] 売上金額を合計
     * [3] 直近6ヶ月分を取得
     *
     * @return 月別売上実績のリスト（直近6ヶ月分）
     */
    @Query("SELECT DATE_FORMAT(order_date, '%Y年%m月') AS month, SUM(total_price) AS total FROM orders GROUP BY month ORDER BY month ASC LIMIT 6")
    List<MonthlySalesDTO> findMonthlySales();

    /*
     * 売上金額上位の商品を取得する
     *
     * 処理の流れ：
     * [1] 注文と商品テーブルを結合
     * [2] 商品ごとに売上を集計
     * [3] 売上金額順に上位5件を取得
     *
     * @return 売上金額上位の商品リスト（最大5件）
     */
    @Query("SELECT p.name AS productName, SUM(o.total_price) AS totalSales " +
            "FROM orders o " +
            "JOIN products p ON o.product_id = p.id " +
            "GROUP BY p.name " +
            "ORDER BY totalSales DESC " +
            "LIMIT 5")
    List<ProductSalesDTO> findTopProductSales();

    /*
     * 時間帯別の注文数を集計する
     *
     * 処理の流れ：
     * [1] 注文時刻を2時間単位の時間帯に分類
     * [2] 時間帯ごとに注文数を集計
     * [3] 営業時間（7-20時）内の注文のみを対象
     *
     * @return 時間帯別の注文数リスト
     */
    @Query("SELECT " +
            "CASE " +
            "    WHEN HOUR(o.order_date) BETWEEN 7 AND 8 THEN '7-9時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 9 AND 10 THEN '9-11時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 11 AND 12 THEN '11-13時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 13 AND 14 THEN '13-15時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 15 AND 16 THEN '15-17時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 17 AND 18 THEN '17-19時' " +
            "    WHEN HOUR(o.order_date) BETWEEN 19 AND 20 THEN '19-21時' " +
            "    ELSE 'その他' " +
            "END AS timeSlot, " +
            "COUNT(*) AS orderCount " +
            "FROM orders o " +
            "WHERE HOUR(o.order_date) BETWEEN 7 AND 20 " +
            "GROUP BY timeSlot " +
            "ORDER BY FIELD(timeSlot, '7-9時', '9-11時', '11-13時', '13-15時', '15-17時', '17-19時', '19-21時')")
    List<TimeSlotOrdersDTO> findOrdersByTimeSlot();

    /*
     * ステータス別の注文数を集計する
     *
     * 処理の流れ：
     * [1] 注文とステータステーブルを結合
     * [2] ステータスごとに注文数を集計
     *
     * @return ステータス別の注文数リスト
     */
    @Query("SELECT s.name AS statusName, COUNT(*) AS orderCount " +
            "FROM orders o " +
            "JOIN status s ON o.status_id = s.id " +
            "GROUP BY s.name")
    List<StatusOrdersDTO> findOrdersByStatus();
}