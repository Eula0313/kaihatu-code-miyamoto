package com.example.pokemon.service;

import com.example.pokemon.entity.Types;

import java.util.List;

/** タイプサービス処理：Service */
public interface TypeService {

    /** タイプの情報を全件取得 */
    List<Types> getAllTypes();
}
