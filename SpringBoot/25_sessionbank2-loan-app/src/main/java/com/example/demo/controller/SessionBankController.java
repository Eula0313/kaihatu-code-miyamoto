package com.example.demo.controller;

import com.example.demo.form.LoanForm;
import com.example.demo.util.Submit;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.SessionAttributes;

@Controller
@SessionAttributes(types = LoanForm.class)
public class SessionBankController {

    // 初期画面（入力フォーム表示）
    @GetMapping("/index")
    public String indexView(LoanForm form) {
        return "index";
    }

    // 確認画面（入力データ表示）
    @PostMapping("/confirm")
    public String confirmView(LoanForm form) {
        return "confirm";
    }

    // 完了画面（セッションデータを保持したまま表示）
    @PostMapping("/result")
        public String resultView(Model model, LoanForm form) {

        //出力設定（課題提出用）
        Submit.setPrint(model);
        return "result";
    }
}