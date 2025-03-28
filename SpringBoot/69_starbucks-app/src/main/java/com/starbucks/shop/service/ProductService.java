// ProductService.java
package com.starbucks.shop.service;

import com.starbucks.admin.repository.OptionRepository;
import com.starbucks.admin.repository.ProductRepository;
import com.starbucks.shop.entity.Product;
import com.starbucks.shop.entity.Option;
import com.starbucks.shop.repository.ShopProductRepository;
import com.starbucks.shop.repository.ShopOptionRepository ;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
public class ProductService {
    private final ShopProductRepository productRepository;
    private final ShopOptionRepository optionRepository;

    public List<Product> findAllAvailableProducts() {
        return productRepository.findByIsAvailableTrue();
    }

    public Optional<Product> findProductById(Integer id) {
        return productRepository.findById(id);
    }

    public List<Option> findAllAvailableOptions() {
        return optionRepository.findByIsAvailableTrue();
    }

    public Optional<Option> findOptionById(Integer id) {
        return optionRepository.findById(id);
    }
}