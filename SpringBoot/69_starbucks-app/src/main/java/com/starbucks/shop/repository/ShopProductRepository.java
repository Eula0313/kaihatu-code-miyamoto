// ProductRepository.java
package com.starbucks.shop.repository;

import com.starbucks.shop.entity.Product;
import org.springframework.data.repository.CrudRepository;
import java.util.List;

public interface ShopProductRepository extends CrudRepository<Product, Integer> {
    List<Product> findByIsAvailableTrue();
}