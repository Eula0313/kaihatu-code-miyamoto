package com.example.demo.repository;

import com.example.demo.entity.Member;
import org.springframework.data.repository.CrudRepository;

public interface MemberCrudRepository extends CrudRepository<Member,Integer> {

}
