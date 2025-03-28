package com.example.demo.service;

import com.example.demo.entity.UserAuth;
import com.example.demo.entity.LoginUser;
import lombok.RequiredArgsConstructor;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.Collections;
import java.util.Optional;

/**
 * カスタム認証サービス
 */
@Service
@RequiredArgsConstructor
public class LoginUserDatailsServiceImpl implements UserDetailsService {

    /** DI */
    private final UserAuthService service;

    @Override
    public UserDetails loadUserByUsername(String username)
            throws UsernameNotFoundException {
        // 「認証テーブル」からデータを取得
        Optional<UserAuth> auth = service.findById(username);

        // 対象データがあれば、UserDetailsの実装クラスを返す
        if (auth.isPresent()) {
            //Optional型からデータを取り出す
            UserAuth authentication = auth.get();
            // 対象データが存在する
            // UserDetailsの実装クラスを返す
            return new LoginUser(authentication.getUsername(),
                    authentication.getPassword(),
                    Collections.emptyList()
            );
        } else {
            // 対象データが存在しない
            throw new UsernameNotFoundException(
                    username + " => 指定しているユーザー名は存在しません");
        }
    }
}