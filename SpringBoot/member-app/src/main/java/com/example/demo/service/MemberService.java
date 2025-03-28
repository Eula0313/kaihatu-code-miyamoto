package com.example.demo.service;

import com.example.demo.entity.Member;

public interface MemberService {
    Iterable<Member> findAll();
    void insertMember(Member member);
}
