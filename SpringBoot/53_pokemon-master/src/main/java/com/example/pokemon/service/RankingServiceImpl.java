package com.example.pokemon.service;

import com.example.pokemon.dto.RankingDTO;
import com.example.pokemon.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class RankingServiceImpl implements RankingService {

    @Autowired
    private UserRepository userRepository;

    @Override
    public List<RankingDTO> getTop10Ranking() {

        // 1. データベースから上位10名のランキング情報を取得
        return userRepository.findTop10ByWinCount();
    }
}
