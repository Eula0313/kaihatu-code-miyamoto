// Order.java
// 注文情報を表すエンティティクラス
// 注文に関連するユーザーや商品、ステータス情報を管理します。
// 関連テーブル: orders
package com.starbucks.admin.entity;

import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

import java.math.BigDecimal;
import java.time.LocalDateTime;

@Table("orders")
@Data
public class Order {
    // 注文ID（自動生成される主キー）
    @Id
    private Long id;

    // 注文を行ったユーザーのID（usersテーブルと関連）
    private Long userId;

    // 注文された商品のID（productsテーブルと関連）
    private Long productId;

    // 選択されたオプションのID（optionsテーブルと関連）
    private Long optionId;

    // 注文のステータスID（statusテーブルと関連）
    private Long statusId;

    // 注文された商品の数量
    private Integer quantity;

    // 商品の単価
    private BigDecimal unitPrice;

    // 注文の合計金額（単価 × 数量）
    private BigDecimal totalPrice;

    // 注文が行われた日時
    private LocalDateTime orderDate;

    // データが作成された日時
    private LocalDateTime createdAt;

    // データが最後に更新された日時
    private LocalDateTime updatedAt;
}
