package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserPokemon {

    @Id
    private Integer userPokemonId;

    private Integer userId;

    private Integer pokemonId;

    private Integer position;
}
