package com.example.pokemon.service;

import com.example.pokemon.dto.UserDTO;
import com.example.pokemon.form.UserForm;

import java.util.List;
import java.util.Optional;

/** ユーザーサービス処理：Service */
public interface UserService {

    /** ユーザー情報を全件取得 */
    List<UserDTO> selectAllUsers();

    /**ユーザーの情報を、idをキーに1件取得 */
    Optional<UserDTO> selectOneUserById(Integer id);

    /** 指定されたユーザーIDのパートナーポケモンIDを取得 */
    List<Integer> getPartnerPokemonIds(Integer userId);

    /** ユーザーを新規登録し、パートナーポケモンを登録 */
    void insertUser(UserForm userForm);

    /** ユーザー情報とパートナーポケモンを更新 */
    void updateUser(Integer id, UserForm userForm);

    /** ユーザーを削除 */
    void deleteUser(Integer id);

}
