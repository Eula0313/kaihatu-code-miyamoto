package com.example.demo.controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class IndexController {
    @GetMapping("hello")
    public String helloView(Model model){

        String inputName="ひろし";
        model.addAttribute("name", inputName);

        return "hello";
    }
}
