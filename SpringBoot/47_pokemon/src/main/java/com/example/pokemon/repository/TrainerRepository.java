package com.example.pokemon.repository;

import com.example.pokemon.entity.Trainer;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;

public interface TrainerRepository extends CrudRepository<Trainer, Integer> {
    @Query("SELECT trainer_id FROM trainer ORDER BY RAND() LIMIT 1")
    Integer getRandomId();
}
