package com.example.demo.controller;

import com.example.demo.entity.Quiz;
import com.example.demo.service.QuizService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class IndexController {

    @Autowired
    QuizService service;

    @GetMapping("index")
    public String indexView() {
        return "index";
    }

    @PostMapping(value="menu", params="select")
    public String listView(Model model) {
        Iterable<Quiz> list = service.findAll();
        model.addAttribute("list",list);
        return "list";
    }
}

