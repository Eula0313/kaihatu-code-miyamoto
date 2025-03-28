package com.starbucks.shop.form;

import com.starbucks.shop.entity.Order;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotNull;
import lombok.Data;


@Data
public class ProductOrderForm {
    @NotNull
    private Integer productId;

    private Integer optionId;

    @NotNull
    @Min(1)
    private Integer quantity;

    private Order.CoffeeSize size;
}

