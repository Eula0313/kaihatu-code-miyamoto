package com.example.pokemon.form;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserRegistrationForm {

    @NotBlank(message = "メールアドレスは必須です")
    @Email(message = "正しいメールアドレス形式で入力してください")
    private String email;

    @NotBlank(message = "トレーナー名は必須です")
    @Size(max = 100, message = "トレーナー名は100文字以内で入力してください")
    private String trainerName;

    @NotBlank(message = "トレーナーアイコンを選択してください")
    private String trainerIcon;
}
