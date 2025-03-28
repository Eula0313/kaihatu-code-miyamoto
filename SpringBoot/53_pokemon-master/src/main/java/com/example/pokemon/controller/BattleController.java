package com.example.pokemon.controller;

import com.example.pokemon.dto.BattleInitializationDTO;
import com.example.pokemon.dto.BattleResultDTO;
import com.example.pokemon.dto.UserDTO;
import com.example.pokemon.service.BattleService;
import com.example.pokemon.service.UserService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import jakarta.servlet.http.HttpSession;

import java.util.List;

/**
 * ポケモンバトル機能のController
 *
 * URLの共通部分: /battle
 * できること：
 * - トレーナー選択画面の表示
 * - バトルの初期化と開始
 * - バトルの実行と結果の処理
 * - バトル結果の表示
 * - トレーナー選択画面への戻り処理
 * - エラー処理
 */
@Controller
@RequestMapping("/battle")
@RequiredArgsConstructor
public class BattleController {

    /** バトルの実行と結果の記録を行うための機能をまとめたもの */
    private final BattleService battleService;

    /** トレーナー（ユーザー）情報の取得と管理を行うための機能をまとめたもの */
    private final UserService userService;

    /**
     * トレーナー選択画面を表示する
     * URL: /battle/select (GET)
     *
     * 処理の流れ：
     * 1. 全トレーナー情報を取得する
     * 2. 取得したトレーナー情報を画面に渡す
     * 3. トレーナー選択画面を表示する
     */
    @GetMapping("/select")
    public String showTrainerSelection(Model model) {
        // 1. 全トレーナー情報を取得する
        model.addAttribute("trainers", userService.selectAllUsers());
        // 2. & 3. トレーナー選択画面を表示する
        return "battle/trainer-selection";
    }

    /**
     * バトルの初期化を行う
     * URL: /battle/initialize (POST)
     *
     * 処理の流れ：
     * 1. 選択されたトレーナーのチェックを行う
     * 2. バトルの初期データを作成する
     * 3. 作成したデータをセッションに保存する
     * 4. バトル画面を表示する
     *
     * エラー時：
     * - 同じトレーナーが選択された場合はエラーメッセージを表示
     * - その他のエラーが発生した場合も適切なメッセージを表示
     */
    @PostMapping("/initialize")
    public String initializeBattle(
            @RequestParam Integer trainer1Id,
            @RequestParam Integer trainer2Id,
            Model model,
            HttpSession session) {
        // 1. 選択されたトレーナーのチェック
        if (trainer1Id.equals(trainer2Id)) {
            model.addAttribute("error", "同じトレーナーを選択することはできません。");
            model.addAttribute("trainers", userService.selectAllUsers());
            return "battle/trainer-selection";
        }

        try {
            // 2. バトルの初期データを作成
            BattleInitializationDTO battleData = battleService.initializeBattle(trainer1Id, trainer2Id);
            // 3. セッションにバトルデータを保存
            session.setAttribute("battleData", battleData);
            model.addAttribute("battleData", battleData);
            // 4. バトル画面を表示
            return "battle/battle-screen";

        } catch (RuntimeException e) {
            model.addAttribute("error", e.getMessage());
            model.addAttribute("trainers", userService.selectAllUsers());
            return "battle/trainer-selection";
        }
    }

    /**
     * バトルを実行し結果を返す
     * URL: /battle/execute (POST)
     *
     * 処理の流れ：
     * 1. セッションからバトルデータを取得する
     * 2. バトル処理を実行する
     * 3. バトル結果をデータベースに記録する
     * 4. 結果をセッションに保存する
     * 5. 結果をJSON形式で返す
     *
     * エラー時：
     * - エラーが発生した場合は例外をスローする
     */
    @PostMapping("/execute")
    @ResponseBody
    public BattleResultDTO executeBattle(HttpSession session) {
        try {
            // 1. セッションからバトルデータを取得
            BattleInitializationDTO battleData = (BattleInitializationDTO) session.getAttribute("battleData");

            if (battleData == null) {
                throw new IllegalStateException("バトルデータが見つかりません。再度トレーナーを選択してください。");
            }

            // 2. バトル処理を実行
            BattleResultDTO result = battleService.executeBattle(battleData);

            // 3. バトル結果を記録
            battleService.recordBattleResult(result);

            // 4. セッションに結果を保存
            session.setAttribute("battleResult", result);

            // 5. 結果を返す
            return result;
        } catch (Exception e) {
            throw new RuntimeException("バトル実行中にエラーが発生しました: " + e.getMessage());
        }
    }

    /**
     * バトル結果画面を表示する
     * URL: /battle/battle-result (GET)
     *
     * 処理の流れ：
     * 1. セッションから結果データを取得する
     * 2. データが無い場合は選択画面にリダイレクトする
     * 3. 結果データを画面に渡す
     * 4. セッションから結果データを削除する
     * 5. 結果画面を表示する
     */
    @GetMapping("/battle-result")
    public String showBattleResult(HttpSession session, Model model) {
        BattleResultDTO result = (BattleResultDTO) session.getAttribute("battleResult");

        if (result == null) {
            return "redirect:/battle/trainer-selection";
        }

        model.addAttribute("result", result);
        session.removeAttribute("battleResult");
        return "battle/battle-result";
    }


    /**
     * トレーナー選択画面に戻る
     * URL: /battle/back-to-selection (GET)
     *
     * 処理の流れ：
     * 1. トレーナー選択画面にリダイレクトする
     */
    @GetMapping("/back-to-selection")
    public String backToSelection() {
        // 1. トレーナー選択画面にリダイレクト
        return "redirect:/battle/trainer-selection";
    }

    /**
     * 予期せぬエラーが発生した場合の処理
     *
     * 処理の流れ：
     * 1. エラーメッセージを設定する
     * 2. トレーナー情報を再取得する
     * 3. トレーナー選択画面を表示する
     */
    @ExceptionHandler(Exception.class)
    public String handleException(Exception e, Model model) {
        // 1. エラーメッセージを設定
        model.addAttribute("error", "エラーが発生しました: " + e.getMessage());
        // 2. トレーナー情報を再取得
        model.addAttribute("trainers", userService.selectAllUsers());
        // 3. トレーナー選択画面を表示
        return "battle/trainer-selection";
    }
}
