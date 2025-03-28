package com.example.demo.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class SecurityController {

    // ダッシュボード表示
    @GetMapping("/index")
    public String indexView() {
        return "index";
    }
}