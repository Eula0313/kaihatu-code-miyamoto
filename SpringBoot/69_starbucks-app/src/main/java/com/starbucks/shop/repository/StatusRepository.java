package com.starbucks.shop.repository;

import com.starbucks.shop.entity.Status;
import org.springframework.data.repository.CrudRepository;

public interface StatusRepository extends CrudRepository<Status, Integer> {
}
