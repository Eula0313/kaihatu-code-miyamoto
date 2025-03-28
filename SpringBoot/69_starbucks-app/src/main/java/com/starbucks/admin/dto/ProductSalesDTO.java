// ProductSalesDTO.java
// 商品ごとの売上データを保持するためのクラス
// 商品名と売上金額を管理します。
package com.starbucks.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import org.springframework.data.relational.core.mapping.Column;

import java.math.BigDecimal;

@Data
@AllArgsConstructor
public class ProductSalesDTO {
    // 商品名（例: "カフェラテ"）
    @Column("productName")
    private String productName;

    // 商品の売上合計金額
    @Column("totalSales")
    private BigDecimal totalSales;
}
