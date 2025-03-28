// OrderRepository.java
package com.starbucks.shop.repository;

import com.starbucks.shop.entity.Order;
import org.springframework.data.repository.CrudRepository;

public interface OrderRepository extends CrudRepository<Order, Integer> {
}