package com.starbucks.admin.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

/*
 * 管理者用ログイン機能を制御するコントローラークラス
 *
 * 管理者向けのログイン画面の表示を担当する
 */
@Controller  // SpringMVCのコントローラーであることを示す
@RequestMapping("/admin")  // 管理者用の基本パスを指定
public class LoginController {

    /*
     * 管理者用ログイン画面を表示する
     *
     * 処理の流れ：
     * [1] ログイン画面のビューを返却
     *
     * @return ログイン画面のビュー名
     */
    @GetMapping("/login")  // GETリクエストに対するマッピング
    public String loginView() {
        // [1] ログイン画面のビューを返却
        return "admin/login";  // ► login.html ビューを返す
    }
}
