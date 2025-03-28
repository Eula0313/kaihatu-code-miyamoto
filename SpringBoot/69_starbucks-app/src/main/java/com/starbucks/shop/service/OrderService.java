// OrderService.java
package com.starbucks.shop.service;

import com.starbucks.shop.entity.Order;
import com.starbucks.shop.entity.Product;
import com.starbucks.shop.entity.Option;
import com.starbucks.shop.form.OrderForm;
import com.starbucks.shop.repository.OrderRepository;
import com.starbucks.shop.repository.ShopProductRepository;
import com.starbucks.shop.repository.ShopOptionRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.Optional;

@Service
@RequiredArgsConstructor
public class OrderService {
    private final OrderRepository orderRepository;
    private final ShopProductRepository productRepository;
    private final ShopOptionRepository optionRepository;

    public Order createOrder(OrderForm orderForm) {
        Product product = productRepository.findById(orderForm.getProductId())
                .orElseThrow(() -> new RuntimeException("Product not found"));

        BigDecimal totalPrice = product.getPrice();

        // 3. サイズによる加算
        Order.CoffeeSize selectedSize = orderForm.getSize(); // enum
        if (selectedSize == Order.CoffeeSize.GRANDE) {
            totalPrice = totalPrice.add(BigDecimal.valueOf(50));
        } else if (selectedSize == Order.CoffeeSize.VENTI) {
            totalPrice = totalPrice.add(BigDecimal.valueOf(100));
        }

        if (orderForm.getOptionId() != null) {
            Option option = optionRepository.findById(orderForm.getOptionId())
                    .orElseThrow(() -> new RuntimeException("Option not found"));
            totalPrice = totalPrice.add(option.getAdditionalPrice());
        }

        totalPrice = totalPrice.multiply(new BigDecimal(orderForm.getQuantity()));

        Order order = new Order();
        order.setUserId(1);
        order.setProductId(orderForm.getProductId());
        order.setOptionId(orderForm.getOptionId());
        order.setQuantity(orderForm.getQuantity());
        order.setUnitPrice(product.getPrice());
        order.setTotalPrice(totalPrice);
        order.setStatusId(1); // 注文受付ステータス
        order.setOrderDate(LocalDateTime.now());
        order.setSize(orderForm.getSize());

        return orderRepository.save(order);
    }

    public Optional<Order> findOrderById(Integer id) {
        return orderRepository.findById(id);
    }
}