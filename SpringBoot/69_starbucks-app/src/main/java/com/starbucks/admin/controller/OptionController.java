package com.starbucks.admin.controller;

import com.starbucks.admin.entity.Option;
import com.starbucks.admin.form.OptionForm;
import com.starbucks.admin.service.OptionService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/*
 * 商品オプション管理機能を制御するコントローラークラス
 *
 * オプションの一覧表示、作成、更新、削除などのCRUD操作を担当する
 */

@Controller  // SpringMVCのコントローラーであることを示す
@RequestMapping("/admin")  // 管理者用の基本パスを指定
public class OptionController {
    @Autowired  // Spring DIコンテナによる依存性の自動注入
    OptionService service;

    /*
     * オプション一覧画面を表示する
     *
     * 処理の流れ：
     * [1] 全オプションデータの取得
     * [2] モデルへのデータ追加
     *
     * @param model ビューに渡すデータを格納するモデル
     * @return オプション一覧画面のビュー名
     */
    @GetMapping("options")
    public String listView(Model model) {
        // [1] 全オプションデータの取得
        Iterable<Option> list = service.findAll();

        // [2] モデルへのデータ追加
        model.addAttribute("options", list);
        return "admin/options/option-index";
    }

    /*
     * オプション新規作成フォームを表示する
     *
     * 処理の流れ：
     * [1] 新規作成フォームの表示
     *
     * @param optionForm 入力フォームを管理するFormオブジェクト
     * @return オプション作成画面のビュー名
     */
    @GetMapping("options/create")
    public String optionInputView(OptionForm optionForm) {
        // [1] 新規作成フォームの表示
        return "admin/options/option-create";
    }

    /*
     * オプションを新規登録する
     *
     * 処理の流れ：
     * [1] 入力値のバリデーション
     * [2] オプションの新規作成
     * [3] 完了メッセージの設定
     *
     * @param form バリデーション対象の入力フォーム
     * @param bindingResult バリデーション結果
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 登録後のリダイレクト先
     */
    @PostMapping("options/create")
    public String createOption(@Validated OptionForm form, BindingResult bindingResult, RedirectAttributes redirectAttributes) {
        // [1] バリデーション
        if (bindingResult.hasErrors()) {
            // ► 入力エラーがある場合は登録画面に戻る
            return "admin/options/option-create";
        }

        // [2] オプションの新規作成
        service.insertOption(form);

        // [3] 完了メッセージの設定
        redirectAttributes.addFlashAttribute("complete", "登録が完了しました");
        return "redirect:/admin/options";
    }

    /*
     * オプション更新フォームを表示する
     *
     * 処理の流れ：
     * [1] 対象オプションの取得
     * [2] モデルへのデータ追加
     *
     * @param id 更新対象のオプションID
     * @param model ビューに渡すデータを格納するモデル
     * @return オプション編集画面のビュー名
     */
    @GetMapping("options/edit/{id}")
    public String editOptionForm(@PathVariable Integer id, Model model) {
        // [1] 対象オプションの取得
        Option option = service.findById(id);

        // [2] モデルへのデータ追加
        model.addAttribute("option", option);
        return "admin/options/option-edit";
    }

    /*
     * オプションを更新する
     *
     * 処理の流れ：
     * [1] オプションの更新処理
     * [2] 完了メッセージの設定
     *
     * @param optionForm 更新内容を含むフォーム
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 更新後のリダイレクト先
     */
    @PostMapping("options/edit")
    public String updateOption(OptionForm optionForm, RedirectAttributes redirectAttributes) {
        // [1] オプションの更新処理
        service.updateOption(optionForm);

        // [2] 完了メッセージの設定
        redirectAttributes.addFlashAttribute("complete", "更新が完了しました");
        return "redirect:/admin/options";
    }

    /*
     * オプションを削除する
     *
     * 処理の流れ：
     * [1] オプションの削除処理
     * [2] 完了メッセージの設定
     * [3] エラー処理
     *
     * @param id 削除対象のオプションID
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 削除後のリダイレクト先
     * @throws IllegalStateException 削除できない状態の場合に発生
     */
    @PostMapping("options/delete/{id}")
    public String deleteProduct(@PathVariable Integer id, RedirectAttributes redirectAttributes) {
        try {
            // [1] オプションの削除処理
            service.deleteOption(id);

            // [2] 完了メッセージの設定
            redirectAttributes.addFlashAttribute("complete", "削除が完了しました");
        } catch (IllegalStateException e) {
            // [3] エラー処理
            // ► エラー内容をログに出力
            System.out.println(e.getMessage());
            // ► エラーメッセージをフラッシュスコープに設定
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/admin/options";
    }
}