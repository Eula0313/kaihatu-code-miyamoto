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
public class MoveForm {

    private Integer moveId;

    @NotBlank(message = "技の名前は必須です")
    @Size(max = 100, message = "技の名前は100文字以内で入力してください")
    private String name;

    @NotNull(message = "技の威力は必須です")
    @Min(value = 1, message = "技の威力は1以上の値を入力してください")
    @Max(value = 999, message = "技の威力は999以下の値を入力してください")
    private Integer power;

    private Boolean newMove;
}
