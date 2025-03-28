package com.example.pokemon.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Column;
import org.springframework.data.relational.core.mapping.Table;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Table("type_effectiveness")
public class TypeEffectiveness {
    @Id
    @Column("type_effectiveness_id")
    private Integer typeEffectivenessId;

    @Column("type_from")
    private Integer typeFrom;

    @Column("type_to")
    private Integer typeTo;

    @Column("effectiveness")
    private Double effectiveness;
}