package com.starbucks.shop.entity;

import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Data
@Table("orders")
public class Order {
    @Id
    private Integer id;
    private Integer userId;
    private Integer productId;
    private Integer optionId;
    private Integer statusId;
    private Integer quantity;
    private BigDecimal unitPrice;
    private BigDecimal totalPrice;
    private LocalDateTime orderDate;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;

    public enum CoffeeSize {
        TALL,
        GRANDE,
        VENTI
    }
    private CoffeeSize size;
}