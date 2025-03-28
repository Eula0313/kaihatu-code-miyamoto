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
    @Table("battles")
    public class Battle {
        @Id
        @Column("battle_id")
        private Integer battleId;    // バトルID

        @Column("user_id")
        private Integer userId;      // ユーザーID

        @Column("opponent_id")
        private Integer opponentId;  // 対戦相手ID

        @Column("result")
        private String result;       // 結果（"WIN" or "LOSE"）
    }

