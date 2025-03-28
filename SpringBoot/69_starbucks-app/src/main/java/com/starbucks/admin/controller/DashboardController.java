package com.starbucks.admin.controller;

import com.starbucks.admin.dto.ChartDataDTO;
import com.starbucks.admin.service.DashboardService;
import com.starbucks.admin.service.OptionService;
import com.starbucks.admin.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.util.HashMap;
import java.util.Map;
/*
 * 管理者用ダッシュボードの制御を行うコントローラークラス
 *
 * 統計情報の表示や各種グラフデータの管理を担当する
 */
@Controller
@RequestMapping("/admin")  // 管理者用の基本パスを指定
public class DashboardController {
    @Autowired  // Spring DIコンテナによる依存性の自動注入
    ProductService productService;

    @Autowired
    OptionService optionService;

    @Autowired
    private DashboardService dashboardService;

    /*
     * 管理者用ダッシュボードのメイン画面を表示する
     *
     * 処理の流れ：
     * [1] 統計情報の取得（商品数、オプション数）
     * [2] 最近追加されたデータの取得
     * [3] グラフ表示用データの作成
     * [4] モデルへのデータ追加
     *
     * @param model ビューに渡すデータを格納するモデル
     * @return ダッシュボード画面のビュー名
     */
    @GetMapping("dashboard")  // GETリクエストのマッピング
    public String dashboardView(Model model) {
        // [1] 統計情報の取得
        // ► 統計カード用のデータを取得（総商品数、販売中商品、総オプション数、有効オプション）
        model.addAttribute("totalProducts", productService.countProducts());
        model.addAttribute("activeProducts", productService.countAvailableProducts());
        model.addAttribute("totalOptions", optionService.countOptions());
        model.addAttribute("activeOptions", optionService.countValidOptions());

        // [2] 最近追加されたデータの取得
        // ► 最新の商品情報を取得
        model.addAttribute("recentProducts", productService.findLatestProduct());
        // ► 最新のオプション情報を取得
        model.addAttribute("recentOptions", optionService.findLatestOption());

        // [3] グラフ表示用データの作成
        // ► グラフデータを格納するためのMapを初期化
        Map<String, Object> chartData = new HashMap<>();

        // ► 各種グラフ用データの取得
        ChartDataDTO monthlySales = dashboardService.getMonthlySales();
        ChartDataDTO topProductSales = dashboardService.getTopProductSales();
        ChartDataDTO ordersByTimeSlot = dashboardService.getOrdersByTimeSlot();
        ChartDataDTO ordersByStatus = dashboardService.getOrdersByStatus();

        // [4] モデルへのデータ追加
        // ► グラフデータをMapに格納
        chartData.put("salesData", monthlySales);        // 月間売上推移
        chartData.put("productData", topProductSales);   // 商品別売上比率
        chartData.put("timeData", ordersByTimeSlot);     // 時間帯別注文数
        chartData.put("statusData", ordersByStatus);     // 注文ステータス内訳

        // ► 作成したグラフデータをモデルに追加
        model.addAttribute("chartData", chartData);

        return "admin/dashboard";  // ► dashboard.html ビューを返す
    }
}