// Option.java
// 商品オプションを表すエンティティクラス
// 追加料金やオプションの利用可能状態を保持します。
// 関連テーブル: options
package com.starbucks.admin.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Table("options")   // DBテーブル名を明示的に指定
public class Option {
    // オプションのID（自動生成される主キー）
    @Id
    private Integer id;

    // オプションの名前（例: "ミルク追加"）
    // TODO

    private String name;

    // オプションの追加料金
    // TODO

    private Integer additionalPrice;

    // オプションが利用可能かどうかを示すフラグ
    // TODO

    private Boolean isAvailable;
}
