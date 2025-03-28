/*
 * オプション管理サービスのインターフェース
 *
 * 商品オプションの操作に関する以下の機能を定義：
 * - オプションの検索、登録、更新、削除
 * - オプション数の集計
 * - 最新オプションの取得
 */
package com.starbucks.admin.service;

import com.starbucks.admin.entity.Option;
import com.starbucks.admin.form.OptionForm;
import java.util.List;

public interface OptionService {

    /*
     * 全オプション情報を取得する
     *
     * @return 全オプションのリスト
     */
    Iterable<Option> findAll();

    /*
     * 指定IDのオプション情報を取得する
     *
     * @param id 取得対象のオプションID
     * @return オプション情報。存在しない場合はnull
     */
    Option findById(Integer id);

    /*
     * 新規オプションを登録する
     *
     * @param optionForm 登録するオプション情報
     */
    void insertOption(OptionForm optionForm);

    /*
     * オプション情報を更新する
     *
     * @param optionForm 更新するオプション情報
     */
    void updateOption(OptionForm optionForm);

    /*
     * オプションを削除する
     *
     * @param id 削除対象のオプションID
     * @throws IllegalStateException 注文で使用中の場合
     */
    void deleteOption(Integer id);

    // 総オプション数を取得
    long countOptions();

    // 有効なオプション数を取得
    long countValidOptions();

    // 最近追加されたオプションを取得
    List<Option> findLatestOption();
}