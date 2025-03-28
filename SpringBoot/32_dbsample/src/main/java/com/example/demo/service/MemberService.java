package com.example.demo.service;

import com.example.demo.entity.Member;

public interface MemberService {
	//一覧取得
	Iterable<Member> findAll();
	
	//追加
	void insertMember(Member member);
}
