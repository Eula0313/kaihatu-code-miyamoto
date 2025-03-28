package com.example.pokemon.service;

import com.example.pokemon.dto.BattleInitializationDTO;
import com.example.pokemon.dto.BattleResultDTO;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

public interface BattleService {
    BattleInitializationDTO initializeBattle(Integer trainer1Id, Integer trainer2Id);
    BattleResultDTO executeBattle(BattleInitializationDTO battleData);
    void recordBattleResult(BattleResultDTO result);
}

