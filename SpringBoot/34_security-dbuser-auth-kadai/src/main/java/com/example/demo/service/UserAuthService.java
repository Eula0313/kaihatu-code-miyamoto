package com.example.demo.service;

import com.example.demo.entity.UserAuth;

import java.util.Optional;

public interface UserAuthService {
    Optional<UserAuth> findById(String username);
}
