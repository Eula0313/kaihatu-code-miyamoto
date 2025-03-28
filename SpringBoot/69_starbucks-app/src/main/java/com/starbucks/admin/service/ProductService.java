/*
 * 商品管理サービスのインターフェース
 *
 * 商品情報の操作に関する以下の機能を定義：
 * - 商品の検索、登録、更新、削除
 * - 商品数の集計
 * - 最新商品の取得
 */
package com.starbucks.admin.service;

import com.starbucks.admin.entity.Product;
import com.starbucks.admin.form.ProductForm;
import java.util.List;

public interface ProductService {

    /*
     * 全商品情報を取得する
     *
     * @return 全商品のリスト
     */
    Iterable<Product> findAll();

    /*
     * 指定IDの商品情報を取得する
     *
     * @param id 取得対象の商品ID
     * @return 商品情報。存在しない場合はnull
     */
    Product findById(Integer id);

    /*
     * 新規商品を登録する
     *
     * @param productForm 登録する商品情報
     */
    void createProduct(ProductForm productForm);

    /*
     * 商品情報を更新する
     *
     * @param productForm 更新する商品情報
     */
    void updateProduct(ProductForm productForm);

    /*
     * 商品を削除する
     *
     * @param id 削除対象の商品ID
     * @throws IllegalStateException 注文で使用中の場合
     */
    void deleteProduct(Integer id);

    // 総商品数を取得
    long countProducts();

    // 販売中の商品数を取得
    long countAvailableProducts();

    // 最近追加された商品情報を取得
    List<Product> findLatestProduct();
}