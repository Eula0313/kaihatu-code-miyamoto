// Product.java
// 商品情報を表すエンティティクラス
// 商品名、説明、価格、画像URLなどの商品情報を保持します。
// 関連テーブル: products
package com.starbucks.admin.entity;

import com.starbucks.shop.entity.Order;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

import java.math.BigDecimal;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Table("products")  // DBテーブル名を明示的に指定
public class Product {
    // 商品ID（自動生成される主キー）
    @Id
    private Integer id;

    // 商品名（例: "カフェラテ"）
    // TODO
    private String name;
    // 商品の説明（例: "ミルクとエスプレッソを合わせた飲み物"）
    // TODO
    private String description;
    // 商品の価格
    // TODO
    private Integer price;
    // 商品が販売可能かどうかを示すフラグ
    // TODO
    private Boolean isAvailable;
    // 商品の画像URL
    // TODO
    private String imageUrl;

    private Order.CoffeeSize size;
}
