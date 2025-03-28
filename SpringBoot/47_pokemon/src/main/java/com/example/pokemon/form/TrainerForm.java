package com.example.pokemon.form;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class TrainerForm {

    private Integer trainerId;

    @NotBlank
    private String trainerName;

    @NotNull
    private Integer trainerLevel;

    @NotNull
    private Integer trainerIconId;

    @NotNull
    private Integer partnerId;

    private Boolean newTrainer;
}
