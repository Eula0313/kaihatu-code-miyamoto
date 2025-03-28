package com.example.demo.controller;

import com.example.demo.form.UserForm;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.SessionAttributes;

@Controller
@SessionAttributes(types = UserForm.class)
public class SecurityController {

    // 初期画面（入力フォーム表示）
    @GetMapping("/index")
    public String indexView(UserForm form) {
        return "index";
    }

    // 確認画面（入力データ表示）
    @PostMapping("/confirm")
    public String confirmView(UserForm form) {
        return "confirm";
    }

    // 完了画面（セッションデータを保持したまま表示）
    @PostMapping("/result")
    public String resultView(UserForm form) {
        return "result";
    }
}