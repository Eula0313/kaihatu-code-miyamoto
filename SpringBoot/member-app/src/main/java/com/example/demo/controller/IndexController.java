package com.example.demo.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

import com.example.demo.entity.Member;
import com.example.demo.form.MemberForm;
import com.example.demo.service.MemberService;
@Controller
public class IndexController {

    @Autowired
    MemberService service;

    @GetMapping("index")
    public String indexView() {
        return "index";
    }

    //	@PostMapping("dbselect")
    @PostMapping(value="menu",params="select")
    public String listView(Model model) {
        Iterable<Member> list = service.findAll();
        model.addAttribute("list",list);
        return "list";
    }
    @PostMapping(value="menu",params="insert")
    public String memberInputView() {
        return "member-input";
    }
    @PostMapping("insert")
    public String memberConfirmView(MemberForm f) {
        Member member = new Member(f.getId(), f.getName());
        service.insertMember(member);
        return "member-confirm";
    }
    }
