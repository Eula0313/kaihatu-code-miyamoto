package com.example.demo.form;

import jakarta.validation.constraints.*;

import lombok.Data;

@Data
public class LoanForm {
    private String name;
    private Integer price;

}