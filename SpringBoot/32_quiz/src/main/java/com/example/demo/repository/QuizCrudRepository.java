package com.example.demo.repository;

import com.example.demo.entity.Quiz;
import org.springframework.data.repository.CrudRepository;

public interface QuizCrudRepository extends CrudRepository<Quiz,Integer> {

}
