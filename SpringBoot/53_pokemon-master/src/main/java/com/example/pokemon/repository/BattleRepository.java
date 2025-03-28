package com.example.pokemon.repository;

import com.example.pokemon.entity.Battle;
import org.springframework.data.jdbc.repository.query.Modifying;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

@Repository
public interface BattleRepository extends CrudRepository<Battle, Integer> {
    @Modifying
    @Query("INSERT INTO battles (user_id, opponent_id, result) VALUES (:userId, :opponentId, :result)")
    void insertBattle(
            @Param("userId") Integer userId,
            @Param("opponentId") Integer opponentId,
            @Param("result") String result
    );
}
