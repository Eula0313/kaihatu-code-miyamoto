package com.example.demo.entity;


import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.userdetails.User;

import java.util.Collection;

public class LoginUser extends User {
    /** 最低限の情報を保持したUserDetails
     * 実装クラスUserを作成する */
    public LoginUser(String username,
                     String password,
                     Collection<? extends GrantedAuthority> authorities) {
        super(username, password, authorities);
    }
}
