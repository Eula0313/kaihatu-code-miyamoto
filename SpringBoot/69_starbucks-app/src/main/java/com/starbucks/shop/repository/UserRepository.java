package com.starbucks.shop.repository;

import com.starbucks.shop.entity.User;
import org.springframework.data.repository.CrudRepository;

public interface UserRepository extends CrudRepository<User, Integer> {
    User findByEmail(String email);
    boolean existsByEmail(String email);  // メールアドレスの重複チェック用
}
