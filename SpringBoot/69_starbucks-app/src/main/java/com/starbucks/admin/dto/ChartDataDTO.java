// ChartDataDTO.java
// グラフ表示用のデータを保持するためのクラス
// 主にX軸ラベルとY軸データをセットで管理します。
package com.starbucks.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.util.List;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class ChartDataDTO {
    // グラフのX軸に表示するラベル（例: 月の名前など）
    private List<String> labels;

    // グラフのY軸に対応するデータ（例: 売上金額や注文数など）
    private List<Object> data;
}