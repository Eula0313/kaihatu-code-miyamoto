// BattleInitializationDTO.java
package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class BattleInitializationDTO {
    private Integer trainer1Id;
    private Integer trainer2Id;
    private PokemonBattleDTO pokemon1;
    private PokemonBattleDTO pokemon2;
}