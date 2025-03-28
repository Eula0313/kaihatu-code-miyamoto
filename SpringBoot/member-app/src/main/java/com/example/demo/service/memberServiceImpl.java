package com.example.demo.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.example.demo.entity.Member;
import com.example.demo.repository.MemberCrudRepository;

@Service
public class memberServiceImpl implements MemberService {
    @Autowired
    MemberCrudRepository repository;

    @Override
    public Iterable<Member> findAll() {
        return repository.findAll();
    }
    @Override
    public void insertMember(Member member) {
        repository.save(member);
    }
}
