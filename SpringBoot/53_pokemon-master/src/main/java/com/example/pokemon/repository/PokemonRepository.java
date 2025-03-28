package com.example.pokemon.repository;

import com.example.pokemon.dto.PokemonDTO;
import com.example.pokemon.entity.Pokemon;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface PokemonRepository extends CrudRepository<Pokemon, Integer> {

    @Query("""
            SELECT
              p.pokemon_id,
              p.name,
              p.hp,
              p.attack,
              p.type_id,
              p.move_id,
              t.type_name_kanji AS type_name_kanji,
              m.name AS move_name
             FROM pokemon p
             JOIN types t ON p.type_id = t.type_id
             JOIN moves m ON p.move_id = m.move_id
             ORDER BY p.pokemon_id ASC
           """)

    //ポケモン情報をタイプや技の詳細を含めて取得
    List<PokemonDTO> selectAllPokemon();

    //指定されたポケモンがUserPokemonテーブルで参照されているか確認
    @Query("SELECT COUNT(*) > 0 FROM user_pokemon WHERE pokemon_id = :pokemonId")
    boolean existsReferencedInUserPokemon(Integer pokemonId);
}
