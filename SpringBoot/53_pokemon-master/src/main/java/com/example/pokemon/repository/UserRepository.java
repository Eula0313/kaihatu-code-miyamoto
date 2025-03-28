package com.example.pokemon.repository;

import com.example.pokemon.entity.Users;
import com.example.pokemon.dto.UserDTO;
import com.example.pokemon.dto.RankingDTO;
import org.springframework.data.jdbc.repository.query.Modifying;
import org.springframework.data.jdbc.repository.query.Query;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.query.Param;
import java.util.List;

/**
 * ユーザー（トレーナー）情報のデータベースアクセスを行うRepository
 *
 * できること：
 * - トレーナーの基本情報と詳細情報の取得
 * - 勝利数による上位10名のランキング情報の取得
 * - トレーナーが対戦履歴に関連付けられているかの確認
 */
public interface UserRepository extends CrudRepository<Users, Integer> {

    /**
     * 全トレーナーの詳細情報を取得する
     *
     * 取得する情報：
     * - トレーナーの基本情報（ID、メール、名前、アイコン、レベル）
     * - パートナーポケモンの名前
     *
     * テーブル結合：
     * 1. usersテーブル（基本情報）
     * 2. user_pokemonテーブル（トレーナーとポケモンの関連）
     * 3. pokemonテーブル（ポケモン情報）
     */
    @Query("""
       SELECT
           u.user_id,
           u.email,
           u.trainer_name,
           u.trainer_icon,
           u.trainer_level,
           GROUP_CONCAT(p.name) AS partner_pokemon_name
       FROM users u
       LEFT JOIN user_pokemon up ON u.user_id = up.user_id
       LEFT JOIN pokemon p ON up.pokemon_id = p.pokemon_id
       GROUP BY u.user_id
       ORDER BY u.user_id ASC
   """)
    List<UserDTO> findAllWithDetails();

    /**
     * 勝利数が多い上位10名のトレーナーランキング情報を取得する
     *
     * 取得する情報：
     * - トレーナーの基本情報（ID、名前、アイコン、レベル）
     * - パートナーポケモンの名前
     * - 対戦成績（勝利数、総対戦数、勝率）
     *
     * 並び順：
     * 1. 勝利数が多い順
     * 2. 勝率が高い順
     *
     * 特記事項：
     * - 対戦履歴がない場合、勝利数と総対戦数は0、勝率も0%となる
     * - パートナーポケモンは登録順が1番目のものを表示
     */
    @Query("""
       SELECT 
           u.user_id as userId,
           u.trainer_name as trainer_name,
           u.trainer_icon as trainer_icon,
           u.trainer_level as trainer_level,
           p.name as partner_pokemon_name,
           COALESCE(SUM(CASE WHEN b.result = 'WIN' THEN 1 ELSE 0 END), 0) as wins,
           COALESCE(COUNT(DISTINCT b.battle_id), 0) as total_battles,
           COALESCE(
               CASE 
                   WHEN COUNT(DISTINCT b.battle_id) > 0 
                   THEN (SUM(CASE WHEN b.result = 'WIN' THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT b.battle_id))
                   ELSE 0.0 
               END,
               0.0
           ) as win_rate
       FROM users u
       LEFT JOIN user_pokemon up ON u.user_id = up.user_id AND up.position = 1
       LEFT JOIN pokemon p ON up.pokemon_id = p.pokemon_id
       LEFT JOIN battles b ON u.user_id = b.user_id
       GROUP BY u.user_id, u.trainer_name, u.trainer_icon, u.trainer_level, p.name
       ORDER BY wins DESC, win_rate DESC
       LIMIT 10
   """)
    List<RankingDTO> findTop10ByWinCount();

    /**
     * 指定されたトレーナーが対戦履歴に関連付けられているかを確認する
     *
     * 確認内容：
     * - battlesテーブルで、トレーナーが対戦者または対戦相手として登録されているか
     *
     * 戻り値：
     * - true: 対戦履歴が存在する
     * - false: 対戦履歴が存在しない
     */
    @Query("SELECT COUNT(*) > 0 FROM battles WHERE user_id = :userId OR opponent_id = :userId")
    boolean existsReferencedInBattles(@Param("userId") Integer userId);

    @Modifying
    @Query("UPDATE users SET partner_pokemon_id = NULL WHERE user_id = :userId")
    void updatePartnerPokemonIdToNull(@Param("userId") Integer userId);

}