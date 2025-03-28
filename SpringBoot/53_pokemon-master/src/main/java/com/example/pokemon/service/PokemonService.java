package com.example.pokemon.service;

import com.example.pokemon.dto.PokemonDTO;
import com.example.pokemon.entity.Moves;
import com.example.pokemon.entity.Pokemon;
import com.example.pokemon.entity.Types;
import com.example.pokemon.form.PokemonForm;

import java.util.List;
import java.util.Optional;

/** ポケモンサービス処理：Service */
public interface PokemonService {

    /** ポケモン情報を全件取得 */
    List<PokemonDTO> selectAllPokemon();

    /** ポケモンの情報を、idをキーに1件取得 */
    Optional<Pokemon> selectOnePokemonById(Integer id);

    /** ポケモンを登録 */
    void insertPokemon(PokemonForm pokemonForm);

    /** ポケモンを更新 */
    void updatePokemon(Integer id, PokemonForm pokemonForm);

    /** ポケモンを削除 */
    void deletePokemon(Integer id);

    List<Pokemon> getAllPokemon();
}
