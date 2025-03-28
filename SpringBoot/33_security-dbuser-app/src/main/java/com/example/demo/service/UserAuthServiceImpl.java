package com.example.demo.service;

import com.example.demo.entity.UserAuth;
import com.example.demo.repository.UserAuthCrudRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class UserAuthServiceImpl implements UserAuthService {

    // 1. UserAuthServiceの実装クラス。
    @Autowired
    UserAuthCrudRepository repository;

    @Override
    public Optional<UserAuth> findById(String username) {
        return repository.findById(username);
    }
}
