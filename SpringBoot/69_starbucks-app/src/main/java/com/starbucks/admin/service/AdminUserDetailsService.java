package com.starbucks.admin.service;

import com.starbucks.admin.entity.AdminUser;
import com.starbucks.admin.repository.AdminUserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

@Service
public class AdminUserDetailsService implements UserDetailsService {

    @Autowired
    private AdminUserRepository repository;

    @Override
    public UserDetails loadUserByUsername(String email) throws UsernameNotFoundException {
        AdminUser adminUser = repository.findByEmail(email);
        if (adminUser == null) {
            throw new UsernameNotFoundException("User not found");
        }
        return User.builder()
                .username(adminUser.getEmail())
                .password(adminUser.getPassword()) // パスワードは事前にエンコードする必要があります
                .roles("ADMIN")
                .build();
    }
}
