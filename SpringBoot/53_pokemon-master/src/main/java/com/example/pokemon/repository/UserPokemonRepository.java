package com.example.pokemon.repository;

import com.example.pokemon.entity.UserPokemon;
import org.springframework.data.jdbc.repository.query.Modifying;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Repository
public interface UserPokemonRepository extends CrudRepository<UserPokemon, Integer> {
    @Query("DELETE FROM user_pokemon WHERE user_id = :userId")
    @Modifying // 更新系のクエリであることを指定
    @Transactional
        // トランザクション内で処理を実行
    void deleteByUserId(@Param("userId") Integer userId);

    // 指定したユーザーIDに関連するポケモンIDをポジション順で取得する
    @Query("SELECT pokemon_id FROM user_pokemon WHERE user_id = :userId ORDER BY position ASC")
    List<Integer> findPokemonIdsByUserId(@Param("userId") Integer userId);

    // 指定したユーザーIDに関連するUserPokemonエンティティをポジション順で取得する
    List<UserPokemon> findByUserIdOrderByPositionAsc(Integer userId);

    List<UserPokemon> findByUserId(Integer trainer2Id);
}
