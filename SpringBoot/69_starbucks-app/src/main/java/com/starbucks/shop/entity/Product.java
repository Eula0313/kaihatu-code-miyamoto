package com.starbucks.shop.entity;

import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Data
@Table("products")
public class Product {
    @Id
    private Integer id;
    private String name;
    private String description;
    private BigDecimal price;
    private Order.CoffeeSize size;
    private Boolean isAvailable;
    private String imageUrl;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;
}