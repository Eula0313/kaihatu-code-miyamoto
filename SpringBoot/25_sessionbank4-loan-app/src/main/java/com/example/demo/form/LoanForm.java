package com.example.demo.form;

import jakarta.validation.constraints.*;

import lombok.Data;

@Data
public class LoanForm {
    @NotEmpty(message = "ユーザー名を入力してください")
    private String name;
    @NotNull(message = "金額を入力してください")
    @DecimalMin(value ="10000", message = "貸出可能額は10,000円からです")
    @DecimalMax(value ="10000000",message ="限度額は10,000,000円です")
    private Integer price;
    @NotNull(message = "年齢を入力してください")
    @DecimalMin(value = "20",message = "20歳未満の方はお借入れできません")
    @DecimalMax(value ="69",message = "70歳以上の方はお借入れできません")
    private Integer age;
}