package com.example.demo.form;

import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.time.LocalDate;

@Data
public class Form {
    private String name;
    private Integer age;
    @DateTimeFormat(iso = DateTimeFormat.ISO.DATE)
    private LocalDate birth;
}
