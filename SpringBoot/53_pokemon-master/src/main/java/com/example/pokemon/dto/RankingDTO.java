package com.example.pokemon.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class RankingDTO {
    private Integer userId;             // トレーナーID
    private String trainerName;         // トレーナー名
    private String trainerIcon;         // トレーナーアイコンのパス
    private Integer trainerLevel;       // トレーナーレベル
    private String partnerPokemonName;  // パートナーポケモン名
    private Integer wins;               // 勝利数
    private Integer totalBattles;       // 総対戦数
    private Double winRate;             // 勝率
}
