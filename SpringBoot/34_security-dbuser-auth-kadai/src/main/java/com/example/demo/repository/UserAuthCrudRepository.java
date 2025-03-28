package com.example.demo.repository;

import com.example.demo.entity.UserAuth;
import org.springframework.data.repository.CrudRepository;

public interface UserAuthCrudRepository extends CrudRepository<UserAuth, String> {
}
