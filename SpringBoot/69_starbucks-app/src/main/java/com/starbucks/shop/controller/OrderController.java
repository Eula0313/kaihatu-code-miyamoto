package com.starbucks.shop.controller;

import com.starbucks.shop.form.OrderForm;
import com.starbucks.shop.form.ProductOrderForm;
import com.starbucks.shop.service.OrderService;
import com.starbucks.shop.service.ProductService;  // 追加
import com.starbucks.shop.entity.Order;
import com.starbucks.shop.entity.Product;  // 追加
import com.starbucks.shop.entity.Option;   // 追加
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import jakarta.validation.Valid;

import java.math.BigDecimal;
import java.util.Optional;  // 追加

@Controller
@RequestMapping("/shop")
@RequiredArgsConstructor
public class OrderController {
    private final OrderService orderService;
    private final ProductService productService;  // 追加

    @PostMapping("/orders/confirm")
    public String confirm(@Valid ProductOrderForm form, BindingResult result, Model model) {
        // 1. バリデーションエラーのチェック
        if (result.hasErrors()) {
            return "redirect:/shop/products/" + form.getProductId();
        }

        // 2. 商品情報の取得
        Optional<Product> product = productService.findProductById(form.getProductId());
        if (product.isEmpty()) {
            // 商品が見つからない場合は商品一覧へリダイレクト
            return "redirect:/shop/products";
        }

        // 3. 商品の基本価格を取得
        BigDecimal totalPrice = product.get().getPrice();

        // サイズ追加料金
        // Tall → +0円
        if (form.getSize() == Order.CoffeeSize.GRANDE) {
            totalPrice = totalPrice.add(BigDecimal.valueOf(50));
        } else if (form.getSize() == Order.CoffeeSize.VENTI) {
            totalPrice = totalPrice.add(BigDecimal.valueOf(100));
        }

        // 4. オプション情報の取得と価格の追加（選択されている場合）
        if (form.getOptionId() != null) {
            Optional<Option> option = productService.findOptionById(form.getOptionId());
            if (option.isPresent()) {
                // オプションの追加料金を加算
                totalPrice = totalPrice.add(option.get().getAdditionalPrice());
                model.addAttribute("option", option.get());
            }
        }

        // 5. 数量を掛けて最終的な合計金額を計算
        totalPrice = totalPrice.multiply(new BigDecimal(form.getQuantity()));

        // 6. 注文フォームの作成と設定
        OrderForm orderForm = new OrderForm();
        orderForm.setProductId(form.getProductId());
        orderForm.setOptionId(form.getOptionId());
        orderForm.setQuantity(form.getQuantity());
        orderForm.setSize(form.getSize());

        // 7. モデルに必要な情報を追加
        model.addAttribute("product", product.get());
        model.addAttribute("orderForm", orderForm);
        model.addAttribute("totalPrice", totalPrice);  // 合計金額を追加

        return "shop/order/confirm";
    }

    @PostMapping("/orders")
    public String create(@ModelAttribute OrderForm orderForm, Model model) {
        Order order = orderService.createOrder(orderForm);
        return "redirect:/shop/orders/" + order.getId() + "/complete";
    }

    @GetMapping("/orders/{id}/complete")
    public String complete(@PathVariable Integer id, Model model) {
        Optional<Order> orderOptional = orderService.findOrderById(id);

        if (orderOptional.isPresent()) {
            Order order = orderOptional.get();
            model.addAttribute("order", order);

            // 商品情報の取得と設定
            productService.findProductById(order.getProductId()).ifPresent(product ->
                    model.addAttribute("product", product)
            );

            // オプション情報の取得と設定（オプションが選択されている場合）
            if (order.getOptionId() != null) {
                productService.findOptionById(order.getOptionId()).ifPresent(option ->
                        model.addAttribute("option", option)
                );
            }

            return "shop/order/complete";
        }

        // 注文が見つからない場合は商品一覧にリダイレクト
        return "redirect:/shop/products";
    }
}