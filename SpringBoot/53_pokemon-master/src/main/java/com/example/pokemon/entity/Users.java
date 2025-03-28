package com.example.pokemon.entity;

import jakarta.validation.constraints.NotEmpty;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Column;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class Users {

    @Id
    private Integer userId;

    private String email;

    private String trainerName;

    private String trainerIcon;

    private Integer trainerLevel;

    private Integer partnerPokemonId;

}
