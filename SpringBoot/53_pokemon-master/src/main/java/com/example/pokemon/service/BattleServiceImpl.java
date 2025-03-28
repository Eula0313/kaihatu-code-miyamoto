package com.example.pokemon.service;

import com.example.pokemon.dto.BattleInitializationDTO;
import com.example.pokemon.dto.BattleResultDTO;
import com.example.pokemon.dto.PokemonBattleDTO;
import com.example.pokemon.entity.*;
import com.example.pokemon.repository.*;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Random;
import java.util.stream.Collectors;

/**
 * ポケモンバトルの処理を管理するサービスクラス
 *
 * このクラスは以下の主要な機能を提供します：
 * - バトルの初期化
 * - バトル実行のロジック
 * - バトル結果の記録
 * - ダメージ計算
 *
 * 各機能はトランザクション管理され、データの整合性を保ちます。
 */
@Service
@RequiredArgsConstructor
public class BattleServiceImpl implements BattleService {
    // 各種リポジトリの依存性注入
    private final UserRepository userRepository;
    private final PokemonRepository pokemonRepository;
    private final MoveRepository movesRepository;
    private final TypeRepository typesRepository;
    private final TypeEffectivenessRepository typeEffectivenessRepository;
    private final BattleRepository battleRepository;
    private final UserPokemonRepository userPokemonRepository;

    /**
     * バトルの初期化を行うメソッド
     *
     * @param trainer1Id 1人目のトレーナーID
     * @param trainer2Id 2人目のトレーナーID
     * @return バトル初期化情報を含むDTO
     *
     * このメソッドは以下の処理を行います：
     * 1. トレーナー1のパートナーポケモンを取得
     * 2. トレーナー2のポケモンをランダムに選択
     * 3. 各ポケモンの詳細情報（HP、技など）を設定
     */
    @Override
    @Transactional
    public BattleInitializationDTO initializeBattle(Integer trainer1Id, Integer trainer2Id) {
        // トレーナー1のパートナーポケモン取得
        Users trainer1 = userRepository.findById(trainer1Id)
                .orElseThrow(() -> new RuntimeException("トレーナー1が見つかりません"));

        Pokemon pokemon1 = pokemonRepository.findById(trainer1.getPartnerPokemonId())
                .orElseThrow(() -> new RuntimeException("トレーナー1のポケモンが見つかりません"));

        // トレーナー2のポケモンをランダム選択
        List<UserPokemon> trainer2Pokemons = userPokemonRepository.findByUserId(trainer2Id);
        if (trainer2Pokemons.isEmpty()) {
            throw new RuntimeException("トレーナー2のポケモンが見つかりません");
        }

        UserPokemon randomUserPokemon = trainer2Pokemons.get(new Random().nextInt(trainer2Pokemons.size()));
        Pokemon pokemon2 = pokemonRepository.findById(randomUserPokemon.getPokemonId())
                .orElseThrow(() -> new RuntimeException("トレーナー2のポケモンが見つかりません"));

        // ポケモンの詳細情報を取得
        PokemonBattleDTO pokemon1DTO = createPokemonBattleDTO(pokemon1);
        PokemonBattleDTO pokemon2DTO = createPokemonBattleDTO(pokemon2);

        return new BattleInitializationDTO(trainer1Id, trainer2Id, pokemon1DTO, pokemon2DTO);
    }

    /**
     * ポケモンの戦闘用データを作成するプライベートメソッド
     *
     * @param pokemon 基本となるポケモンエンティティ
     * @return バトル用のポケモンDTO
     *
     * このメソッドでは：
     * 1. ポケモンのタイプ情報を取得
     * 2. ポケモンの技情報を取得
     * 3. バトルに必要な情報をDTOにまとめる
     */
    private PokemonBattleDTO createPokemonBattleDTO(Pokemon pokemon) {
        Types type = typesRepository.findById(pokemon.getTypeId())
                .orElseThrow(() -> new RuntimeException("タイプが見つかりません"));

        Moves move = movesRepository.findById(pokemon.getMoveId())
                .orElseThrow(() -> new RuntimeException("技が見つかりません"));

        return new PokemonBattleDTO(
                pokemon.getPokemonId(),
                pokemon.getName(),
                pokemon.getHp(),
                pokemon.getHp(),
                pokemon.getAttack(),
                pokemon.getTypeId(),    // typeId追加
                type.getTypeNameKanji(),
                move.getName(),
                move.getPower()
        );
    }

    /**
     * バトルを実行するメソッド
     *
     * @param battleData バトルの初期化データ
     * @return バトル結果を含むDTO
     *
     * このメソッドは以下の処理を実行します：
     * 1. 両方のポケモンが戦えなくなるまで交互に攻撃
     * 2. 各ターンのダメージ計算
     * 3. バトルログの記録
     * 4. 勝者と敗者の決定
     */
    @Override
    @Transactional
    public BattleResultDTO executeBattle(BattleInitializationDTO battleData) {
        StringBuilder battleLog = new StringBuilder();
        PokemonBattleDTO pokemon1 = battleData.getPokemon1();
        PokemonBattleDTO pokemon2 = battleData.getPokemon2();

        int currentHp1 = pokemon1.getCurrentHp();
        int currentHp2 = pokemon2.getCurrentHp();

        boolean isFirstPokemonTurn = true;

        while (currentHp1 > 0 && currentHp2 > 0) {
            if (isFirstPokemonTurn) {
                // ポケモン1の攻撃
                int damage = calculateDamage(pokemon1, pokemon2);
                currentHp2 -= damage;
                battleLog.append(String.format("%sの%sが%sに%dのダメージを与えた！\n",
                        pokemon1.getName(), pokemon1.getMoveName(), pokemon2.getName(), damage));
            } else {
                // ポケモン2の攻撃
                int damage = calculateDamage(pokemon2, pokemon1);
                currentHp1 -= damage;
                battleLog.append(String.format("%sの%sが%sに%dのダメージを与えた！\n",
                        pokemon2.getName(), pokemon2.getMoveName(), pokemon1.getName(), damage));
            }

            isFirstPokemonTurn = !isFirstPokemonTurn;
        }

        // 勝者と敗者を決定
        boolean isPokemon1Winner = currentHp1 > 0;
        PokemonBattleDTO winner = isPokemon1Winner ? pokemon1 : pokemon2;
        PokemonBattleDTO loser = isPokemon1Winner ? pokemon2 : pokemon1;
        Integer winnerTrainerId = isPokemon1Winner ? battleData.getTrainer1Id() : battleData.getTrainer2Id();
        Integer loserTrainerId = isPokemon1Winner ? battleData.getTrainer2Id() : battleData.getTrainer1Id();

        battleLog.append(String.format("\n%sの勝利！\n", winner.getName()));

        return new BattleResultDTO(winnerTrainerId, loserTrainerId, battleLog.toString(), winner, loser);
    }

    /**
     * バトル結果をデータベースに記録するメソッド
     *
     * @param result バトル結果のDTO
     *
     * このメソッドでは：
     * 1. 勝者の記録をデータベースに保存
     * 2. 敗者の記録をデータベースに保存
     * エラーが発生した場合は RuntimeException をスロー
     */
    @Override
    @Transactional
    public void recordBattleResult(BattleResultDTO result) {
        try {
            // 勝者の記録
            battleRepository.insertBattle(
                    result.getWinnerTrainerId(),
                    result.getLoserTrainerId(),
                    "WIN"
            );

            // 敗者の記録
            battleRepository.insertBattle(
                    result.getLoserTrainerId(),
                    result.getWinnerTrainerId(),
                    "LOSE"
            );
        } catch (Exception e) {
            throw new RuntimeException("バトル結果の保存に失敗しました: " + e.getMessage(), e);
        }
    }

    /**
     * ダメージを計算するプライベートメソッド
     *
     * @param attacker 攻撃するポケモンのDTO
     * @param defender 防御するポケモンのDTO
     * @return 計算されたダメージ値
     *
     * ダメージ計算の仕組み：
     * 1. タイプ相性を取得（見つからない場合は等倍=1.0を使用）
     * 2. (攻撃力 + 技の威力) × タイプ相性 でダメージを算出
     * 3. デバッグ用にログを出力
     */
    private int calculateDamage(PokemonBattleDTO attacker, PokemonBattleDTO defender) {
        // タイプ相性によるダメージ補正を取得
        TypeEffectiveness typeEffectiveness = null;
        try {
            typeEffectiveness = typeEffectivenessRepository
                    .findByTypeFromAndTypeTo(attacker.getTypeId(), defender.getTypeId());
        } catch (Exception e) {
            // エラーログを出力
            System.err.println("タイプ相性の取得に失敗しました: " + e.getMessage());
            System.err.println("攻撃側タイプID: " + attacker.getTypeId());
            System.err.println("防御側タイプID: " + defender.getTypeId());
        }

        // タイプ相性が見つからない場合は等倍(1.0)とする
        double effectiveness = (typeEffectiveness != null) ? typeEffectiveness.getEffectiveness() : 1.0;

        // 攻撃力 + 技の威力 × タイプ相性で計算
        int damage = (int)((attacker.getAttack() + attacker.getMovePower()) * effectiveness);

        // デバッグ用ログ出力
        System.out.println(String.format(
                "ダメージ計算: %s(%d) -> %s(%d), 攻撃力: %d, 技威力: %d, タイプ相性: %.1f, 最終ダメージ: %d",
                attacker.getName(),
                attacker.getTypeId(),
                defender.getName(),
                defender.getTypeId(),
                attacker.getAttack(),
                attacker.getMovePower(),
                effectiveness,
                damage
        ));

        return damage;
    }
}