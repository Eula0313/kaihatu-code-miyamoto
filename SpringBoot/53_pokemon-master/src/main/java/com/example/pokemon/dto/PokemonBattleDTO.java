package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class PokemonBattleDTO {
    private Integer pokemonId;
    private String name;
    private Integer currentHp;
    private Integer maxHp;
    private Integer attack;
    private Integer typeId;
    private String typeName;
    private String moveName;
    private Integer movePower;
}