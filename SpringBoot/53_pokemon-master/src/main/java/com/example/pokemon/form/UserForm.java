package com.example.pokemon.form;

import jakarta.validation.constraints.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class UserForm {

    private Integer userId;

    @NotBlank(message = "メールアドレスは必須です")
    @Email(message = "有効なメールアドレスを入力してください")
    private String email;

    @NotBlank(message = "トレーナー名は必須です")
    @Size(max = 100, message = "トレーナー名は100文字以内で入力してください")
    private String trainerName;

    @NotBlank(message = "トレーナーアイコンは必須です")
    private String trainerIcon;

    @NotNull(message = "トレーナーレベルは必須です")
    @Min(value = 1, message = "レベルは1以上の値を入力してください")
    private Integer trainerLevel;

    @NotEmpty(message = "パートナーポケモンIDを3体指定してください")
    private Integer[] partnerPokemonIds = new Integer[3];

    private Boolean newUser;
}
