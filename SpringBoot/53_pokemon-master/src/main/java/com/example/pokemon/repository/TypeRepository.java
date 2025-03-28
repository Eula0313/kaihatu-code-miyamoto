package com.example.pokemon.repository;

import com.example.pokemon.entity.Types;
import org.springframework.data.repository.CrudRepository;

public interface TypeRepository extends CrudRepository<Types, Integer> {

}
