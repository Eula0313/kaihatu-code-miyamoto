// MonthlySalesDTO.java
// 月ごとの売上データを保持するためのクラス
// 各月の名前と合計売上金額を管理します。
package com.starbucks.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Data;

import java.math.BigDecimal;

@Data
@AllArgsConstructor
public class MonthlySalesDTO {
    // 売上月の名前（例: "2024年01月"）
    private String month;

    // 売上合計金額
    private BigDecimal total;
}