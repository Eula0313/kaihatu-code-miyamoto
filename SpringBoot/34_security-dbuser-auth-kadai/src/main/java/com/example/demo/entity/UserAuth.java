package com.example.demo.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserAuth {
    /** ユーザー名 */
    @Id
    private String username;
    /** パスワード */
    private String password;
    /** 権限 */
    private Role authority;  //認可追加
}