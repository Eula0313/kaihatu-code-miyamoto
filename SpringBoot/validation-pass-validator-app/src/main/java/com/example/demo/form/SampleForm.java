package com.example.demo.form;

import jakarta.validation.constraints.AssertTrue;
import lombok.Data;

import java.util.Objects;

@Data
public class SampleForm {
    //パスワード
    private String password;
    //確認用パスワード
    private String confirmPassword;

}
