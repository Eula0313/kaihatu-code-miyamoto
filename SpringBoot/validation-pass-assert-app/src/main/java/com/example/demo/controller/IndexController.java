package com.example.demo.controller;

import com.example.demo.form.SampleForm;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class IndexController {
    //入力画面の表示
    @GetMapping("input")
    public String showForm(SampleForm form){
        return "entry";
    }

    //相関項目チェックの実行
    @PostMapping("check")
    public String check(@Validated SampleForm form,
                        BindingResult bindingResult,Model model){

        //バリデーションの実施
        if(bindingResult.hasErrors()){
            return "entry";
        }

        //modelにメッセージを登録（ビューに送る）
        model.addAttribute("message","入力に問題ありません");

        //ビューの表示（result.html）
        return "result";
    }
}
