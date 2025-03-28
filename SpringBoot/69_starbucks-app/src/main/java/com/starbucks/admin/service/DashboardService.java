/*
 * ダッシュボード用データ集計サービスクラス
 *
 * 以下の機能を提供する：
 * - 月次売上の集計
 * - 商品別売上ランキングの集計
 * - 時間帯別注文数の集計
 * - ステータス別注文数の集計
 */
package com.starbucks.admin.service;

// 必要なクラスをインポート
import com.starbucks.admin.dto.*;
import com.starbucks.admin.repository.AdminOrderRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import java.util.List;
import java.util.stream.Collectors;

// サービスクラスとして定義
@Service
public class DashboardService {

    @Autowired
    AdminOrderRepository ordersRepository;

    /*
     * 月間売上データをグラフ表示用の形式に変換する
     *
     * 処理の流れ：
     * [1] リポジトリから月間売上データを取得
     * [2] X軸ラベル（月）を抽出
     * [3] Y軸データ（売上金額）を抽出
     *
     * @return グラフ表示用のデータ（ChartDataDTO）
     */
    public ChartDataDTO getMonthlySales() {
        // [1] 月間売上のデータを取得
        List<MonthlySalesDTO> salesData = ordersRepository.findMonthlySales();

        // [2] 各月のラベルを取得
        List<String> labels = salesData.stream().map(MonthlySalesDTO::getMonth).collect(Collectors.toList());

        // [3] 各月の売上データを取得
        List<Object> data = salesData.stream().map(MonthlySalesDTO::getTotal).collect(Collectors.toList());

        //サンプルデータ
//        List<String> labels = List.of("2024-01", "2024-02", "2024-03", "2024-04");
//        List<Object> data = List.of(580000, 620000, 750000, 680000, 810000);

        // ラベルとデータをChartDataDTOに格納して返す
        return new ChartDataDTO(labels, data);
    }

    /*
     * 商品別売上データをグラフ表示用の形式に変換する
     *
     * 処理の流れ：
     * [1] リポジトリから商品別売上データを取得
     * [2] X軸ラベル（商品名）を抽出
     * [3] Y軸データ（売上金額）を抽出
     *
     * @return グラフ表示用のデータ（ChartDataDTO）
     */
    public ChartDataDTO getTopProductSales() {
        // [1] 商品別売上データを取得
        List<ProductSalesDTO> productSales = ordersRepository.findTopProductSales();

        // [2] 各商品のラベル（商品名）を取得
        List<String> labels = productSales.stream().map(ProductSalesDTO::getProductName).collect(Collectors.toList());

        // [3] 各商品の売上データを取得
        List<Object> data = productSales.stream().map(ProductSalesDTO::getTotalSales).collect(Collectors.toList());

        //サンプルデータ
//        List<String> labels = List.of("カフェラテ", "コーヒー", "フラペチーノ", "抹茶");
//        List<Object> data = List.of(40, 30, 20, 10);

        // ラベルとデータをChartDataDTOに格納して返す
        return new ChartDataDTO(labels, data);
    }

    /*
     * 時間帯別注文数データをグラフ表示用の形式に変換する
     *
     * 処理の流れ：
     * [1] リポジトリから時間帯別注文データを取得
     * [2] X軸ラベル（時間帯）を抽出
     * [3] Y軸データ（注文数）を抽出
     *
     * @return グラフ表示用のデータ（ChartDataDTO）
     */
    public ChartDataDTO getOrdersByTimeSlot() {
        // [1] 時間帯別の注文数データを取得
        List<TimeSlotOrdersDTO> timeSlotOrders = ordersRepository.findOrdersByTimeSlot();

        // [2] 時間帯のラベルを取得
        List<String> labels = timeSlotOrders.stream().map(TimeSlotOrdersDTO::getTimeSlot).collect(Collectors.toList());

        // [3] 各時間帯の注文数を取得
        List<Object> data = timeSlotOrders.stream().map(TimeSlotOrdersDTO::getOrderCount).collect(Collectors.toList());

        //サンプルデータ
//        List<String> labels = List.of("7-9時", "9-11時", "11-13時", "13-15時", "15-17時", "17-19時");
//        List<Object> data = List.of(40, 30, 20, 10, 30, 20);

        // ラベルとデータをChartDataDTOに格納して返す
        return new ChartDataDTO(labels, data);
    }

    /*
     * ステータス別注文数データをグラフ表示用の形式に変換する
     *
     * 処理の流れ：
     * [1] リポジトリからステータス別注文データを取得
     * [2] X軸ラベル（ステータス名）を抽出
     * [3] Y軸データ（注文数）を抽出
     *
     * @return グラフ表示用のデータ（ChartDataDTO）
     */
    public ChartDataDTO getOrdersByStatus() {
        // [1] ステータス別の注文数データを取得
        List<StatusOrdersDTO> statusOrders = ordersRepository.findOrdersByStatus();

        // [2] 各ステータスのラベル（例: "準備中", "完了"）を取得
        List<String> labels = statusOrders.stream().map(StatusOrdersDTO::getStatusName).collect(Collectors.toList());

        // [3] 各ステータスの注文数を取得
        List<Object> data = statusOrders.stream().map(StatusOrdersDTO::getOrderCount).collect(Collectors.toList());

//        List<String> labels = List.of("注文受付", "準備中", "準備完了", "完了");
//        List<Object> data = List.of(40, 30, 20, 10);

        // ラベルとデータをChartDataDTOに格納して返す
        return new ChartDataDTO(labels, data);
    }
}