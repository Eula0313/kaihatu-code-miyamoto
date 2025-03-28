package com.example.pokemon.repository;

import com.example.pokemon.entity.TypeEffectiveness;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

@Repository
public interface TypeEffectivenessRepository extends CrudRepository<TypeEffectiveness, Integer> {
    @Query("SELECT * FROM type_effectiveness WHERE type_from = :typeFrom AND type_to = :typeTo")
    TypeEffectiveness findByTypeFromAndTypeTo(@Param("typeFrom") Integer typeFrom, @Param("typeTo") Integer typeTo);
}