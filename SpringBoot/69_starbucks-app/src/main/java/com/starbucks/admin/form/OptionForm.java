// OptionForm.java
// 商品オプションの入力フォームを表すクラス
// ユーザーがオプション情報（名前、追加料金、利用可能状態）を入力する際に使用します。
// 対応エンティティ: Option
package com.starbucks.admin.form;

import jakarta.validation.constraints.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class OptionForm {
    // オプションID（編集時に使用。新規作成時はnull）

    private Integer id;

    // オプション名（必須、最大100文字）
    // バリデーション:
    // - 必須入力（空白不可）
    // - 最大100文字まで
    // TODO

    @NotBlank(message = "オプション名を入力してください")
    @Size(max = 100, message = "オプション名は100文字以内で入力してください")
    private String name;

    // オプションの追加料金（必須、0.00～10,000.00の範囲）
    // バリデーション:
    // - 必須入力
    // - 最小値0.00以上
    // - 最大値10,000.00以下
    // TODO

    @NotNull(message = "追加料金を入力してください")
    @DecimalMin(value = "0.00", message = "追加料金は0以上にしてください")
    @DecimalMax(value = "10000.00", message = "追加料金は10000以下にしてください")
    private Integer additionalPrice;

    // オプションが利用可能かどうか（必須、true: 利用可能、false: 利用不可）
    // バリデーション:
    // - 必須入力
    // TODO

    @NotNull(message = "利用可能フラグを選択してください")
    private Boolean isAvailable;

}
