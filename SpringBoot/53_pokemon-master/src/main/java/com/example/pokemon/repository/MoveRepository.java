package com.example.pokemon.repository;

import com.example.pokemon.entity.Moves;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;

public interface MoveRepository extends CrudRepository<Moves, Integer> {
    // pokemon テーブルで参照されているかを確認
    @Query("SELECT COUNT(*) > 0 FROM pokemon WHERE move_id = :moveId")
    boolean existsReferencedInPokemon(Integer moveId);
}