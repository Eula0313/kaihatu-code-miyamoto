package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class Pokemon {

    @Id
    private Integer pokemonId;

    private String name;

    private Integer hp;

    private Integer attack;

    private Integer typeId;

    private Integer moveId;

}
