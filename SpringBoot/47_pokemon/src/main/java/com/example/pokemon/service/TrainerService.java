package com.example.pokemon.service;

import com.example.pokemon.entity.Trainer;

import java.util.Optional;

/** トレーナーサービス処理：Service */
public interface TrainerService {

    /** トレーナー情報を全件取得します */
    Iterable<Trainer> selectAll();

    /** トレーナー情報を、idをキーに1件取得します */
    Optional<Trainer> selectOneById(Integer id);

    /** トレーナーを登録します */
    void insertTrainer(Trainer trainer);

    /** トレーナーを更新します */
    void updateTrainer(Trainer trainer);

    /** トレーナーを削除します */
    void deleteTrainerById(Integer id);
}
