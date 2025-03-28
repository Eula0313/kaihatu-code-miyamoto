package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

import java.util.List;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserDTO {
    @Id
    private Integer userId;

    private String email;

    private String trainerName;

    private String trainerIcon;

    private Integer trainerLevel;

    private String partnerPokemonName;

    private List<Integer> partnerPokemonIds;
}
