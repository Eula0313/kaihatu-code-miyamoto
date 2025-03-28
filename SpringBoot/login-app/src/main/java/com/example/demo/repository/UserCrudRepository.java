package com.example.demo.repository;

import org.springframework.data.repository.CrudRepository;
import com.example.demo.entity.User;

public interface UserCrudRepository extends CrudRepository<User, String> {
}
