package com.starbucks.shop.service;

import com.starbucks.shop.entity.User;
import com.starbucks.shop.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;
import org.springframework.security.core.userdetails.User.UserBuilder;

@Service
public class ShopUserDetailsService implements UserDetailsService {

    @Autowired
    private UserRepository repository;

    @Override
    public UserDetails loadUserByUsername(String email) throws UsernameNotFoundException {
        User user = repository.findByEmail(email);
        if (user == null) {
            throw new UsernameNotFoundException("User not found");
        }

        UserBuilder builder = org.springframework.security.core.userdetails.User.builder();
        return builder
                .username(user.getEmail())
                .password(user.getPassword())
                .roles("USER")
                .build();
    }
}