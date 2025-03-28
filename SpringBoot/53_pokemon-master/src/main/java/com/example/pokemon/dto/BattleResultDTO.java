// BattleResultDTO.java
package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class BattleResultDTO {
    private Integer winnerTrainerId;
    private Integer loserTrainerId;
    private String battleLog;
    private PokemonBattleDTO winnerPokemon;
    private PokemonBattleDTO loserPokemon;
}