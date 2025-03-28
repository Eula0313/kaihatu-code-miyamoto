package com.example.demo.controller;

import com.example.demo.entity.Member;
import com.example.demo.form.MemberForm;
import com.example.demo.service.MemberService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class IndexController {

    @Autowired
    MemberService service;

    @GetMapping("index")
    public String indexView() {
        return "index";
    }

    @PostMapping(value="menu", params="select")
    public String listView(Model model) {
        Iterable<Member> list = service.findAll();
        model.addAttribute("list",list);
        return "list";
    }

    @PostMapping(value="menu", params="insert")
    public String memberInputView() {
        return "member-input";
    }

    @PostMapping("confirm")
    public String memberConfirmView(MemberForm m) {
        Member member = new Member(m.getMemberId(),m.getMemberName());
        service.insertMember(member);
        return "member-confirm";
    }
}

