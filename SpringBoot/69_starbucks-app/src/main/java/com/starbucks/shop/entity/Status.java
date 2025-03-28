package com.starbucks.shop.entity;

import lombok.Data;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

@Data
@Table("status")
public class Status {
    @Id
    private Integer id;
    private String name;
    private String description;
}