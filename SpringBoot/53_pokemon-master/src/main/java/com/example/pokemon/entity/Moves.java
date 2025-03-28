package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class Moves {

    @Id
    private Integer moveId;

    private String name;

    private Integer power;

}
