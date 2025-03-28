package com.example.pokemon.form;

import jakarta.validation.constraints.Max;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotNull;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserPokemonForm {

    @NotNull(message = "ポケモンを選択してください")
    private Integer pokemonId;

    @NotNull(message = "ポジションを選択してください")
    @Min(value = 1, message = "ポジションは1から3の間で選択してください")
    @Max(value = 3, message = "ポジションは1から3の間で選択してください")
    private Integer position;
}
