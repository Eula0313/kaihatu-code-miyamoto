package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class Trainer {

    @Id
    private Integer trainerId;

    private String trainerName;

    private Integer trainerLevel;

    private Integer trainerIconId;

    private Integer partnerId;
}
