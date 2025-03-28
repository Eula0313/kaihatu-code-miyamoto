package com.example.demo.service;

import com.example.demo.entity.UserAuth;

import java.util.Optional;

public interface UserAuthService {

    // 1. 認証に関連する操作を定義するサービスインターフェース
    Optional<UserAuth> findById(String username);
}
