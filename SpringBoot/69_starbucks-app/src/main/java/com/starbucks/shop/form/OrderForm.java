package com.starbucks.shop.form;

import com.starbucks.shop.entity.Order;
import lombok.Data;
import java.math.BigDecimal;

@Data
public class OrderForm {
    private Integer productId;
    private Integer optionId;
    private Integer quantity;
    private BigDecimal totalPrice;
    private Order.CoffeeSize size;
}