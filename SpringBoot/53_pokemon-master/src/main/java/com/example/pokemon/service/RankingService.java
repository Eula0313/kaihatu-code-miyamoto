package com.example.pokemon.service;

import com.example.pokemon.dto.RankingDTO;
import java.util.List;

/**
 * トレーナーのランキング情報を提供するServiceのインターフェース
 *
 * 提供する機能：
 * - 勝利数の多い上位10名のトレーナーランキング取得
 *
 * 利用対象：
 * - ランキング画面の表示
 * - ランキング情報の取得が必要な他の機能
 */
public interface RankingService {

    /**
     * 勝利数の多い上位10名のトレーナーランキングを取得する
     *
     * 取得する情報：
     * - トレーナーの基本情報（名前、アイコン、レベル）
     * - パートナーポケモンの名前
     * - 対戦成績（勝利数、総対戦数、勝率）
     *
     * 並び順：
     * 1. 勝利数が多い順
     * 2. 同じ勝利数の場合は勝率が高い順
     *
     * @return 上位10名分のランキング情報のリスト
     */

    List<RankingDTO> getTop10Ranking();

}