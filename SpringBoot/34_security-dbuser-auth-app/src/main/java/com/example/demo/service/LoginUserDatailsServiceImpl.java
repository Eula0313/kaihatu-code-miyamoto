package com.example.demo.service;

import com.example.demo.entity.Role;
import com.example.demo.entity.UserAuth;
import com.example.demo.entity.LoginUser;
import lombok.RequiredArgsConstructor;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.Collections;
import java.util.ArrayList;
import java.util.List;
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
                    getAuthorityList(authentication.getAuthority())
//                    Collections.emptyList()
            );
        } else {
            // 対象データが存在しない
            throw new UsernameNotFoundException(
                    username + " => 指定しているユーザー名は存在しません");
        }
    }

    /**
     * 認可追加
     * 権限情報をリストで取得する
     */
    private List<GrantedAuthority> getAuthorityList(Role role) {
        // 権限リスト
        List<GrantedAuthority> authorities = new ArrayList<>();
        // 列挙型からロールを取得
        authorities.add(new SimpleGrantedAuthority(role.name()));
        // ADMIN ロールの場合、USERの権限も付与
//        if (role == Role.ADMIN) {
//            authorities.add(
//                    new SimpleGrantedAuthority(Role.STAFF.toString()));
//        }
        return authorities;
    }
}