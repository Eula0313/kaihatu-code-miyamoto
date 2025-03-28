package com.example.demo.service;

import com.example.demo.entity.Quiz;

public interface QuizService {
	//一覧取得
	Iterable<Quiz> findAll();
}
