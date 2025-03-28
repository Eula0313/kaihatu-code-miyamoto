/*
 * 管理者ユーザーリポジトリインターフェース
 *
 * 管理者ユーザー情報のCRUD操作と、以下の機能を提供する：
 * - メールアドレスによる管理者検索
 */
package com.starbucks.admin.repository;

import com.starbucks.admin.entity.AdminUser;
import org.springframework.data.repository.CrudRepository;

public interface AdminUserRepository extends CrudRepository<AdminUser, Integer> {

    /*
     * メールアドレスに一致する管理者ユーザーを検索する
     *
     * 処理の流れ：
     * [1] 指定されたメールアドレスをもとに管理者を検索
     *
     * @param email 検索対象のメールアドレス
     * @return 該当する管理者ユーザー。存在しない場合はnull
     */
    AdminUser findByEmail(String email);
}