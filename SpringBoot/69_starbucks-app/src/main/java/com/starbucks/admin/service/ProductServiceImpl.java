package com.starbucks.admin.service;

import com.starbucks.admin.entity.Option;
import com.starbucks.admin.entity.Product;
import com.starbucks.admin.form.ProductForm;
import com.starbucks.admin.repository.ProductRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/*
 * 商品管理サービスの実装クラス
 *
 * 商品情報の操作に関するビジネスロジックを実装する
 */
@Service
public class ProductServiceImpl implements ProductService {

    @Autowired
    ProductRepository productRepository;

    /*
     * 全商品情報を取得する
     *
     * 処理の流れ：
     * TODO:[1] リポジトリから全商品を取得
     *
     * @return 全商品のリスト
     */
    @Override
    public Iterable<Product> findAll() {
        // TODO:[1] 全商品を取得
        return productRepository.findAll();
    }

    /*
     * 指定IDの商品情報を取得する
     *
     * 処理の流れ：
     * TODO:[1] IDによる商品の検索
     *
     * @param id 取得対象の商品ID
     * @return 商品情報。存在しない場合はnull
     */
    @Override
    public Product findById(Integer id) {
        // TODO:[1] IDによる商品の検索
        return productRepository.findById(id).orElse(null);
    }

    /*
     * 新規商品を登録する
     *
     * 処理の流れ：
     * TODO:[1] フォームからエンティティを生成
     * TODO:[2] 商品情報を保存
     *
     * @param productForm 登録する商品情報
     */
    @Override
    public void createProduct(ProductForm productForm) {
        // TODO:[1] フォームからエンティティを生成
        Product product = new Product();

        product.setName(productForm.getName());
        product.setDescription(productForm.getDescription());
        product.setPrice(productForm.getPrice());
        product.setSize(productForm.getSize());
        product.setIsAvailable(productForm.getIsAvailable());
        product.setImageUrl(productForm.getImageUrl());

        // TODO:[2] 商品情報を保存
        productRepository.save(product);
    }

    /*
     * 商品情報を更新する
     *
     * 処理の流れ：
     * TODO:[1] フォームからエンティティを生成
     * TODO:[2] 商品情報を保存
     *
     * @param productForm 更新する商品情報
     */
    @Override
    public void updateProduct(ProductForm productForm) {
        // TODO:[1] フォームからエンティティを生成
        Product product = productRepository.findById(productForm.getId()).orElse(null);
        if (product == null) {
            return;
        }

        product.setName(productForm.getName());
        product.setDescription(productForm.getDescription());
        product.setPrice(productForm.getPrice());
        product.setSize(productForm.getSize());
        product.setIsAvailable(productForm.getIsAvailable());
        product.setImageUrl(productForm.getImageUrl());

        // TODO:[2] 商品情報を保存
        productRepository.save(product);
    }

    /*
     * 商品を削除する
     *
     * 処理の流れ：
     * TODO:[1] 注文での使用有無を確認
     * TODO:[2] 商品の削除
     *
     * @param id 削除対象の商品ID
     * @throws IllegalStateException 商品が注文で使用されている場合
     */
    @Override
    public void deleteProduct(Integer id) {
        // TODO:[1] 商品が注文済みかチェック
        if (productRepository.existsReferencedInOrder(id)) {
            throw new IllegalStateException("この商品は注文されているため削除できません");
        }

        // TODO:[2] 商品の削除
        productRepository.deleteById(id);
    }

    /*
     * 商品の総数を取得する
     *
     * @return TODO:商品の総数
     */
    @Override
    public long countProducts() {
        // TODO:商品数
        return productRepository.count();
    }

    /*
     * 販売中の商品数を取得する
     *
     * @return TODO:販売中の商品数
     */
    @Override
    public long countAvailableProducts() {
        // TODO:販売中の商品
        return productRepository.countByIsAvailableTrue();
    }

    /*
     * 最近追加された商品を取得する
     *
     * @return TODO:最新の商品リスト
     */
    @Override
    public List<Product> findLatestProduct() {
        // TODO:最近追加された商品
        return productRepository.findLatestProduct();
    }
}