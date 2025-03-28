package com.example.pokemon.service;

import com.example.pokemon.entity.Moves;
import com.example.pokemon.form.MoveForm;

import java.util.Optional;

/** 技サービス処理：Service */
public interface MoveService {

    /** 技の情報を全件取得 */
    Iterable<Moves> selectAllMove();

    /** 技の情報を、idをキーに1件取得 */
    Optional<Moves> selectOneMoveById(Integer id);

    /** 技を登録 */
    void insertMove(MoveForm moveForm);

    /** 技を更新 */
    void updateMove(Integer id, MoveForm moveForm);

    /** 技を削除 */
    void deleteMove(Integer id);
}
