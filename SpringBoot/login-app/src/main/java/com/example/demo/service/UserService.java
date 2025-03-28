package com.example.demo.service;

import com.example.demo.entity.User;

public interface UserService {
    Iterable<User> findAll();
    void insertUser(User user);
    User findByMail(String mail);
}
