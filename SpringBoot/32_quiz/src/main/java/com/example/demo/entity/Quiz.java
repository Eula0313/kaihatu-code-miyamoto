package com.example.demo.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Column;

@Data
@AllArgsConstructor
public class Quiz {
    @Id
    private Integer id;

    private String question;

    private Boolean answer;

    private String author;
}
