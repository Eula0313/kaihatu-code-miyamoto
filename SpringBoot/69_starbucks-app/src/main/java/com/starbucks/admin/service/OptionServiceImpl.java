/*
 * オプション管理サービスの実装クラス
 *
 * オプション情報の操作に関するビジネスロジックを実装する
 */
package com.starbucks.admin.service;

import com.starbucks.admin.entity.Option;
import com.starbucks.admin.entity.Product;
import com.starbucks.admin.form.OptionForm;
import com.starbucks.admin.repository.OptionRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import java.math.BigDecimal;
import java.util.Arrays;
import java.util.List;

@Service
public class OptionServiceImpl implements OptionService {

    @Autowired
    OptionRepository optionRepository;

    /*
     * 全オプション情報を取得する
     *
     * 処理の流れ：
     * TODO:[1] リポジトリから全オプションを取得
     *
     * @return 全オプションのリスト
     */
    @Override
    public Iterable<Option> findAll() {
        // TODO:[1] 全オプションを取得
        return optionRepository.findAll();
    }

    /*
     * 指定IDのオプション情報を取得する
     *
     * 処理の流れ：
     * TODO:[1] IDによるオプションの検索
     *
     * @param id 取得対象のオプションID
     * @return オプション情報。存在しない場合はnull
     */
    @Override
    public Option findById(Integer id) {

        // TODO:[1] IDによるオプションの検索
        return optionRepository.findById(id).orElse(null);
    }

    /*
     * 新規オプションを登録する
     *
     * 処理の流れ：
     * TODO:[1] フォームからエンティティを生成
     * TODO:[2] オプション情報を保存
     *
     * @param optionForm 登録するオプション情報
     */
    @Override
    public void insertOption(OptionForm optionForm) {
        // TODO:[1] フォームからエンティティを生成
        Option option = new Option();

        option.setName(optionForm.getName());
        option.setAdditionalPrice(optionForm.getAdditionalPrice());
        option.setIsAvailable(optionForm.getIsAvailable());

        // TODO:[2] オプション情報を保存
        optionRepository.save(option);
    }

    /*
     * オプション情報を更新する
     *
     * 処理の流れ：
     * TODO:[1] フォームからエンティティを生成
     * TODO:[2] オプション情報を保存
     *
     * @param optionForm 更新するオプション情報
     */
    @Override
    public void updateOption(OptionForm optionForm) {
        // TODO:[1] フォームからエンティティを生成
        Option option = optionRepository.findById(optionForm.getId())
                .orElseThrow(() -> new IllegalArgumentException(
                        "更新対象のオプションが見つかりません。id=" + optionForm.getId()));

        option.setName(optionForm.getName());
        option.setAdditionalPrice(optionForm.getAdditionalPrice());
        option.setIsAvailable(optionForm.getIsAvailable());

        // TODO:[2] オプション情報を保存
        optionRepository.save(option);
    }

    /*
     * オプションを削除する
     *
     * 処理の流れ：
     * [1] 注文での使用有無を確認
     * TODO:[2] オプションの削除
     *
     * @param id 削除対象のオプションID
     * @throws IllegalStateException オプションが注文で使用されている場合
     */
    @Override
    public void deleteOption(Integer id) {
        // [1] オプションが注文済みかチェック
        if (optionRepository.existsReferencedInOrder(id)) {
            throw new IllegalStateException("このオプションは注文されているため削除できません");
        }
        // TODO:[2] オプションの削除
        optionRepository.deleteById(id);
    }

    /*
     * オプションの総数を取得する
     *
     * @return TODO:オプションの総数
     */
    @Override
    public long countOptions() {
        // TODO:総オプション数
        return optionRepository.count();
    }

    /*
     * 有効なオプションの数を取得する
     *
     * @return TODO:有効なオプションの数
     */
    @Override
    public long countValidOptions() {
        // TODO:有効オプション数
        return optionRepository.countByIsAvailableTrue();
    }

    /*
     * 最近追加されたオプションを取得する
     *
     * @return TODO:最新のオプションリスト
     */
    @Override
    public List<Option> findLatestOption() {
        // TODO:最近追加されたオプション
        return optionRepository.findLatestOption();
    }
}