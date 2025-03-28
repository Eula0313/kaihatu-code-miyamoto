package com.starbucks.shop.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.web.authentication.logout.SecurityContextLogoutHandler;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

@Controller
@RequestMapping("/shop")
public class UserLoginController {

    @GetMapping("/login")
    public String loginView() {
        return "shop/login";
    }

    @PostMapping("/logout")
    public String logout(HttpServletRequest request, HttpServletResponse response) {
        // セキュリティコンテキストを取得
        Authentication auth = SecurityContextHolder.getContext().getAuthentication();
        if (auth != null) {
            // ログアウト処理
            new SecurityContextLogoutHandler().logout(request, response, auth);
        }
        // ログイン画面にリダイレクト
        return "redirect:/shop/products";
    }
}