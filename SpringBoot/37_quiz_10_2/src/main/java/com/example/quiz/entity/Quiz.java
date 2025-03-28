package com.example.quiz.entity;

import lombok.Data;
import org.springframework.data.annotation.Id;

@Data
public class Quiz {
    @Id
    private Integer id;
    private String question;
    private Boolean answer;
    private String author;

    // 引数を持つコンストラクタを手動で追加
    public Quiz(Integer id, String question, Boolean answer, String author) {
        this.id = id;
        this.question = question;
        this.answer = answer;
        this.author = author;
    }

    // デフォルトコンストラクタ
    public Quiz() {
    }
}