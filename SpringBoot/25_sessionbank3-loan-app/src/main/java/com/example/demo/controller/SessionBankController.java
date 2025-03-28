package com.example.demo.controller;

import com.example.demo.form.LoanForm;
import com.example.demo.util.Submit;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.SessionAttributes;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.support.SessionStatus;

@Controller
@SessionAttributes(types = LoanForm.class)
public class SessionBankController {

    // 初期画面（入力フォーム表示）
    @GetMapping("/index")
    public String indexView(LoanForm form, Model model) {
        return "index";
    }

    // 確認画面（入力データ表示、バリデーションチェックを行う）
    @PostMapping("/confirm")
    public String confirmView(@Validated LoanForm form, BindingResult bindingResult) {
        boolean hasErrors = bindingResult.hasErrors();
        if (bindingResult.hasErrors()) {
            // エラーがあれば入力画面に戻る
            return "index";
        }
        // 問題なければ確認画面へ
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
