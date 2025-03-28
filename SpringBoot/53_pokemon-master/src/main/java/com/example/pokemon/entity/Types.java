package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class Types {

    @Id
    private Integer typeId;

    private String typeNameKana;

    private String typeNameKanji;

}
