// StatusOrdersDTO.java
// 注文のステータスごとの注文数を保持するためのクラス
// ステータス名とそのステータスに該当する注文数を管理します。
package com.starbucks.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import org.springframework.data.relational.core.mapping.Column;

@Data
@AllArgsConstructor
public class StatusOrdersDTO {
    // 注文ステータス名（例: "準備中"）
    @Column("statusName")
    private String statusName;

    // 該当するステータスの注文数
    @Column("orderCount")
    private Long orderCount;
}
