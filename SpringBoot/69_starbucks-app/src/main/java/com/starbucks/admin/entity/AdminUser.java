// AdminUser.java
// 管理者ユーザーを表すエンティティクラス
// 管理者の基本情報（名前、メールアドレス、パスワードなど）を保持します。
// 関連テーブル: admin_users
package com.starbucks.admin.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

import java.time.LocalDateTime;

@Data
@AllArgsConstructor
@Table("admin_users")
public class AdminUser {
    // 管理者のID（自動生成される主キー）
    @Id
    private Integer id;

    // 管理者の名前
    private String name;

    // 管理者のメールアドレス（ユニーク制約あり）
    private String email;

    // 管理者のログイン用パスワード
    private String password;

    // データが作成された日時
    private LocalDateTime createdAt;

    // データが最後に更新された日時
    private LocalDateTime updatedAt;
}
