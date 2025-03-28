// 表示用DTO (PokemonDTO.java)
package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class PokemonDTO {

    @Id
    private Integer pokemonId;

    private String name;

    private Integer hp;

    private Integer attack;

    private Integer typeId;

    private Integer moveId;

    private String typeNameKanji;

    private String moveName;


}