package com.example.demo.form;

import jakarta.validation.constraints.AssertTrue;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import lombok.Data;

import java.util.Objects;

@Data
public class UserForm {

    @NotBlank(message = "ユーザーIDを入力して下さい")
    private String mail;

    @NotBlank(message = "パスワードを入力して下さい")
    @Size(min = 2, max = 10, message = "パスワードは {min} 文字以上 {max} 文字以内で入力してください")
    private String password;
    private String confirmPassword;
    private String user_name;
    @AssertTrue(message = "パスワードが一致しません")
    public boolean isSamePassword() {
        return Objects.equals(password, confirmPassword);
    }
}
