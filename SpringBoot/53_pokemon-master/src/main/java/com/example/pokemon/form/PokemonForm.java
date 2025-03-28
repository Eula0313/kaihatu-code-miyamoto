package com.example.pokemon.form;

import jakarta.validation.constraints.Max;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Size;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class PokemonForm {

    private Integer pokemonId;

    @NotBlank(message = "ポケモンの名前は必須です")
    @Size(max = 100, message = "ポケモンの名前は100文字以内で入力してください")
    private String name;

    @NotNull(message = "HPは必須です")
    @Min(value = 1, message = "HPは1以上の値を入力してください")
    @Max(value = 999, message = "HPは999以下の値を入力してください")
    private Integer hp;

    @NotNull(message = "攻撃力は必須です")
    @Min(value = 1, message = "攻撃力は1以上の値を入力してください")
    @Max(value = 999, message = "攻撃力は999以下の値を入力してください")
    private Integer attack;

    @NotNull(message = "タイプは必須です")
    private Integer typeId;

    @NotNull(message = "技は必須です")
    private Integer moveId;

    private Boolean newPokemon;

}
