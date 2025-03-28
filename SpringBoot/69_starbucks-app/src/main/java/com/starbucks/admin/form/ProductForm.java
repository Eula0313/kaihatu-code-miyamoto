// ProductForm.java
// 商品の入力フォームを表すクラス
// ユーザーが商品情報（名前、説明、価格、販売状態、画像URL）を入力する際に使用します。
// 対応エンティティ: Product
package com.starbucks.admin.form;

import com.starbucks.shop.entity.Order;
import jakarta.validation.constraints.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;

@Data
@AllArgsConstructor
@NoArgsConstructor
public class ProductForm {
    // 商品ID（編集時に使用。新規作成時はnull）
    private Integer id;

    // 商品名（必須、最大100文字）
    // バリデーション:
    // - 必須入力（空白不可）
    // - 最大100文字まで
    // TODO
    @NotBlank(message = "商品名を入力してください")
    @Size(max = 100, message = "商品名は100文字以内で入力してください")
    private String name;

    // 商品の説明（任意、最大65535文字）
    // バリデーション:
    // - 最大65535文字まで
    // TODO
    @Size(max = 65535, message = "商品説明は65535文字以内で入力してください")
    private String description;

    // 商品の価格（必須、小数点以下2桁までの数値）
    // バリデーション:
    // - 必須入力
    // - 整数部は最大8桁、小数部は最大2桁
    // - 最小値0.00以上
    // - 最大値99,999,999.99以下
    // TODO
    @NotNull(message = "価格を入力してください")
    @Min(value = 0, message = "価格は0以上にしてください")
    @Max(value = 99999999, message = "価格は99999999以下にしてください")
    private Integer price;
    // 商品の販売状態（必須、true: 販売中、false: 販売停止）
    // バリデーション:
    // - 必須入力
    // TODO
    @NotNull(message = "販売状態を選択してください")
    private Boolean isAvailable;

    // 商品画像のURL（任意、最大255文字、正しいURL形式のみ許可）
    // バリデーション:
    // - 最大255文字まで
    // - 正しいURL形式のみ許可（画像形式: png, jpg, jpeg, webp, gif, svg）
    // TODO:バリデーションの追加
    @Size(max = 255, message = "画像URLは255文字以内で入力してください")
    @Pattern(
            regexp = "(^$|^https?://.*(\\.(png|jpg|webp|gif))$)",
            message = "画像URLはhttp/httpsで始まり、png|jpg|webp|gif|のいずれかの拡張子を含む必要があります"
    )
    private String imageUrl;

    @NotNull(message = "サイズを選択してください")
    private Order.CoffeeSize size;
}
