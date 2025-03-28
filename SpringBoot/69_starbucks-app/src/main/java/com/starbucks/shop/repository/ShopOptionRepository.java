package com.starbucks.shop.repository;

import com.starbucks.shop.entity.Option;
import org.springframework.data.repository.CrudRepository;
import java.util.List;

public interface ShopOptionRepository extends CrudRepository<Option, Integer> {
    List<Option> findByIsAvailableTrue();
}