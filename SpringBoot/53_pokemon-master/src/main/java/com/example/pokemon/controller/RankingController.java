package com.example.pokemon.controller;

import com.example.pokemon.dto.RankingDTO;
import com.example.pokemon.service.RankingService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.util.List;

@Controller
@RequestMapping("/ranking")
public class RankingController {

    @Autowired
    private RankingService rankingService;

    @GetMapping
    public String showRanking(Model model) {

        // 1. 上位10名分のランキングデータを取得
        List<RankingDTO> rankings = rankingService.getTop10Ranking();

        // 2. 画面表示用のデータとして設定
        model.addAttribute("rankings", rankings);

        // 3. ランキング一覧画面を表示
        return "ranking/list";
    }
}
