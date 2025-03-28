package com.example.pokemon.controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class MenuController {

    @GetMapping("/menu")
    public String menu(Model model) {
        // 必要に応じて追加の情報をテンプレートに渡す
        return "menu";
    }
}
