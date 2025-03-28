package com.starbucks.admin.controller;

import com.starbucks.admin.entity.Product;
import com.starbucks.admin.form.ProductForm;
import com.starbucks.admin.service.ImageService;
import com.starbucks.admin.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MaxUploadSizeExceededException;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
/*
 * 商品管理機能を制御するコントローラークラス
 *
 * 商品のCRUD操作と画像アップロード処理を担当する
 */
@Controller  // SpringMVCのコントローラーであることを示す
@RequestMapping("/admin")  // 管理者用の基本パスを指定
public class ProductController {

    @Autowired  // Spring DIコンテナによる依存性の自動注入
    ProductService productService;

    @Autowired
    ImageService imageService;

    /*
     * 商品一覧画面を表示する
     *
     * 処理の流れ：
     * [1] 全商品データの取得
     * [2] モデルへのデータ追加
     *
     * @param model ビューに渡すデータを格納するモデル
     * @return 商品一覧画面のビュー名
     */
    @GetMapping("products")
    public String listView(Model model) {
        // [1] 全商品データの取得
        Iterable<Product> productList = productService.findAll();

        // [2] モデルへのデータ追加
        model.addAttribute("products", productList);
        return "admin/products/product-index";
    }

    /*
     * 商品新規作成フォームを表示する
     *
     * 処理の流れ：
     * [1] 新規作成フォームの表示
     *
     * @param productForm 入力フォームを管理するFormオブジェクト
     * @return 商品作成画面のビュー名
     */
    @GetMapping("products/create")
    public String productInputView(ProductForm productForm) {
        // [1] 新規作成フォームの表示
        return "admin/products/product-create";
    }

    /*
     * 商品を新規登録する
     *
     * 処理の流れ：
     * [1] 入力値のバリデーション
     * [2] 画像ファイルのアップロード処理
     * [3] 商品情報の登録
     * [4] 完了メッセージの設定
     *
     * @param productForm バリデーション対象の入力フォーム
     * @param imageFile アップロードされた画像ファイル
     * @param bindingResult バリデーション結果
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 登録後のリダイレクト先
     * @throws RuntimeException 画像アップロード処理に失敗した場合
     */
    @PostMapping("products/create")
    public String store(@Validated ProductForm productForm,
                        BindingResult bindingResult,
                        @RequestPart("imageFile") MultipartFile imageFile,
                        RedirectAttributes redirectAttributes) {
        // [1] 入力値のバリデーション
        if (bindingResult.hasErrors()) {
            // ► 入力エラーがある場合は登録画面に戻る
            return "admin/products/product-create";
        }

        try {
            // [2] 画像ファイルのアップロード処理
            if (!imageFile.isEmpty()) {
                // ► 画像をアップロードしURLを取得
                String imageUrl = imageService.uploadImage(imageFile);
                productForm.setImageUrl(imageUrl);
            }

            // [3] 商品情報の登録
            productService.createProduct(productForm);

            // [4] 完了メッセージの設定
            redirectAttributes.addFlashAttribute("complete", "登録が完了しました");

        } catch (RuntimeException e) {
            // ► 画像アップロード失敗時のエラー処理
            redirectAttributes.addFlashAttribute("error", "画像のアップロードに失敗しました: " + e.getMessage());
            return "redirect:/admin/products/product-create";
        }

        return "redirect:/admin/products";
    }

    /*
     * 商品更新フォームを表示する
     *
     * 処理の流れ：
     * [1] 対象商品の取得
     * [2] モデルへのデータ追加
     *
     * @param id 更新対象の商品ID
     * @param model ビューに渡すデータを格納するモデル
     * @return 商品編集画面のビュー名
     */
    @GetMapping("products/edit/{id}")
    public String editProductForm(@PathVariable Integer id, Model model) {
        // [1] 対象商品の取得
        Product product = productService.findById(id);

        // [2] モデルへのデータ追加
        model.addAttribute("product", product);
        return "admin/products/product-edit";
    }

    /*
     * 商品を更新する
     *
     * 処理の流れ：
     * [1] 画像ファイルの処理
     * [2] 商品情報の更新
     * [3] 完了メッセージの設定
     *
     * @param productForm 更新内容を含むフォーム
     * @param imageFile 新しい画像ファイル（存在する場合）
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 更新後のリダイレクト先
     */
    @PostMapping("products/edit/{id}")
    public String updateProduct(ProductForm productForm,
                                @RequestPart("imageFile") MultipartFile imageFile,
                                RedirectAttributes redirectAttributes) {
        // [1] 画像ファイルの処理
        if (!imageFile.isEmpty()) {
            // ► 新しい画像をアップロードしURLを取得
            String imageUrl = imageService.uploadImage(imageFile);
            productForm.setImageUrl(imageUrl);
        }

        // [2] 商品情報の更新
        productService.updateProduct(productForm);

        // [3] 完了メッセージの設定
        redirectAttributes.addFlashAttribute("complete", "更新が完了しました");
        return "redirect:/admin/products";
    }

    /*
     * 商品を削除する
     *
     * 処理の流れ：
     * [1] 商品の削除処理
     * [2] 完了メッセージの設定
     * [3] エラー処理
     *
     * @param id 削除対象の商品ID
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @return 削除後のリダイレクト先
     * @throws IllegalStateException 削除できない状態の場合に発生
     */
    @PostMapping("products/delete/{id}")
    public String deleteProduct(@PathVariable Integer id, RedirectAttributes redirectAttributes) {
        try {
            // [1] 商品の削除処理
            productService.deleteProduct(id);

            // [2] 完了メッセージの設定
            redirectAttributes.addFlashAttribute("complete", "削除が完了しました");
        } catch (IllegalStateException e) {
            // [3] エラー処理
            // ► エラー内容をログに出力
            System.out.println(e.getMessage());
            // ► エラーメッセージをフラッシュスコープに設定
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/admin/products";
    }

    /*
     * ファイルアップロードサイズ超過時のエラーハンドリング
     *
     * 処理の流れ：
     * [1] エラーメッセージの設定
     * [2] リダイレクト先の判定
     *
     * @param redirectAttributes リダイレクト時のフラッシュメッセージを格納するオブジェクト
     * @param referer 遷移元のURL
     * @return エラー発生前の画面へのリダイレクト先
     */
    @ExceptionHandler(MaxUploadSizeExceededException.class)
    public String handleMaxUploadSizeExceededException(
            RedirectAttributes redirectAttributes,
            @RequestHeader(value = "Referer", required = false) String referer) {
        // [1] エラーメッセージの設定
        redirectAttributes.addFlashAttribute("error", "アップロードしたファイルのサイズが制限を超えています。最大サイズは10MBです。");

        // [2] リダイレクト先の判定
        // ► 新規作成画面からの場合
        if (referer != null && referer.contains("/admin/products/create")) {
            return "redirect:/admin/products/create";
        }
        // ► 編集画面からの場合
        else if (referer != null && referer.contains("/admin/products/edit")) {
            return "redirect:" + referer;
        }

        // ► デフォルトのリダイレクト先
        return "redirect:/admin/products";
    }
}