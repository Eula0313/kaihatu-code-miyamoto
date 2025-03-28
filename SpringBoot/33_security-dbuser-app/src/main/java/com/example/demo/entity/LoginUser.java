package com.example.demo.entity;


import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.userdetails.User;

import java.util.Collection;

// 1. UserDetailsの実装クラス
public class LoginUser extends User {
    public LoginUser(String username,
                     String password,
                     Collection<? extends GrantedAuthority> authorities) {
        super(username, password, authorities);
    }
}
