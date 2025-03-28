package com.example.demo.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.example.demo.entity.User;
import com.example.demo.repository.UserCrudRepository;

@Service
public class userServiceImpl implements UserService {

    @Autowired
    private UserCrudRepository repository;

    @Override
    public Iterable<User> findAll() {
        return repository.findAll();
    }

    @Override
    public void insertUser(User user) {
        repository.save(user);
    }

    @Override
    public User findByMail(String mail) {
        return repository.findByMail(mail);
    }

    @Override
    public boolean isValidUser(String mail, String password) {
        User user = repository.findByMail(mail);

        if (user != null && user.getPassword().equals(password)) {
            return true;
        }
        return false;
    }
}
