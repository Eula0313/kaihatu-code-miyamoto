package com.example.quiz.repository;

import com.example.quiz.entity.Quiz;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;

public interface QuizRepository extends CrudRepository<Quiz,Integer> {
    @Query("SELECT id FROM quiz ORDER BY RANDOM() limit 1")
    Integer getRandomId();
}
