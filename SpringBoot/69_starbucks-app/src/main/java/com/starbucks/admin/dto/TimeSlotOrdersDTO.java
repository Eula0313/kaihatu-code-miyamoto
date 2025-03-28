// TimeSlotOrdersDTO.java
// 時間帯ごとの注文数を保持するためのクラス
// 時間帯とその時間帯の注文数を管理します。
package com.starbucks.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import org.springframework.data.relational.core.mapping.Column;

@Data
@AllArgsConstructor
public class TimeSlotOrdersDTO {
    // 時間帯（例: "7-9時"）
    @Column("timeSlot")
    private String timeSlot;

    // 該当時間帯の注文数
    @Column("orderCount")
    private Long orderCount;
}
