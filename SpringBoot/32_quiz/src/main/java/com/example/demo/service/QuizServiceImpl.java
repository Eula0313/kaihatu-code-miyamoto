package com.example.demo.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.example.demo.entity.Quiz;
import com.example.demo.repository.QuizCrudRepository;

@Service
public class QuizServiceImpl implements QuizService {

	@Autowired
	QuizCrudRepository repository;
	
	@Override
	public Iterable<Quiz> findAll() {
		return repository.findAll();
	}
	
}

