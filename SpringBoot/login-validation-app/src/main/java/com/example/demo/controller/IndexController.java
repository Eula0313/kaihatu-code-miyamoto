package com.example.demo.controller;

import com.example.demo.entity.User;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

import com.example.demo.form.UserForm;
import com.example.demo.service.UserService;

@Controller
public class IndexController {

    @Autowired
    UserService service;

    @GetMapping("index")
    public String indexView(Model model) {
        model.addAttribute("userForm", new UserForm());
        return "index";
    }



    @PostMapping("login")
    public String userLogin(@Validated UserForm form, BindingResult bindingResult, Model model) {
        if (bindingResult.hasErrors()) {
            return "index";
        }

        if (service.isValidUser(form.getMail(), form.getPassword())) {
            User user = service.findByMail(form.getMail());
            model.addAttribute("user_name", user.getUser_name());

            return "login_sucess";
        } else {
            return "login_fail";
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
