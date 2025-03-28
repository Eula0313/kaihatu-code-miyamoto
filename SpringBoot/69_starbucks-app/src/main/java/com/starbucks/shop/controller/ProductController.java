package com.starbucks.shop.controller;

import com.starbucks.shop.entity.Product;
import com.starbucks.shop.entity.Option;
import com.starbucks.shop.form.ProductOrderForm;
import com.starbucks.shop.service.ProductService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Optional;

@Controller("shopProductController")
@RequestMapping("/shop")
@RequiredArgsConstructor
public class ProductController {
    private final ProductService productService;

    @GetMapping("/products")
    public String list(Model model) {
        model.addAttribute("products", productService.findAllAvailableProducts());
        return "shop/product/list";
    }

    @GetMapping("/products/{id}")
    public String detail(@PathVariable Integer id, Model model) {

        // 1. 商品IDから商品情報を検索
        Optional<Product> productOptional = productService.findProductById(id);

        if (productOptional.isPresent()) {
            // 2.1 商品情報を取得
            Product product = productOptional.get();

            // 2.2 注文フォームの初期化
            ProductOrderForm orderForm = new ProductOrderForm();
            orderForm.setProductId(product.getId());  // 商品IDを設定
            orderForm.setQuantity(1);                 // デフォルトの数量を1に設定

            // 2.3 オプション情報の取得
            List<Option> availableOptions = productService.findAllAvailableOptions();

            // 2.4 画面表示用のデータを設定
            model.addAttribute("product", product);           // 商品情報
            model.addAttribute("options", availableOptions);  // オプション一覧
            model.addAttribute("productOrderForm", orderForm); // 注文フォーム

            // 2.5 商品詳細画面を表示
            return "shop/product/detail";
        } else {
            // 3. 商品が見つからない場合は商品一覧画面にリダイレクト
            return "redirect:/shop/products";
        }
    }
}
