package com.example.demo.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import com.example.demo.entity.User;
import com.example.demo.form.UserForm;
import com.example.demo.service.UserService;

@Controller
public class IndexController {

    @Autowired
    UserService service;

    @GetMapping("index")
    public String indexView() {
        return "index";
    }

    @PostMapping("login")
    public String userLogin(UserForm form, Model model, RedirectAttributes redirectAttributes) {
        User user = service.findByMail(form.getMail());


        if (user != null && user.getPassword().equals(form.getPassword())) {
            redirectAttributes.addFlashAttribute("user_name", user.getUser_name());
            return "redirect:/login_sucess";
        } else {
            return "redirect:/login_fail";
        }
    }

    @GetMapping("login_sucess")
    public String loginSuccessView(Model model) {
        return "login_sucess";
    }

    @GetMapping("login_fail")
    public String userLoginFail() {
        return "login_fail";
    }
}
