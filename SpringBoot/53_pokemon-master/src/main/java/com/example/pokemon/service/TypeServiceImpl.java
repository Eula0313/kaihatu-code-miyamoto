package com.example.pokemon.service;

import com.example.pokemon.entity.Types;
import com.example.pokemon.repository.TypeRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@Transactional
public class TypeServiceImpl implements TypeService {

    @Autowired
    private TypeRepository typeRepository;

    @Override
    public List<Types> getAllTypes() {
        return (List<Types>) typeRepository.findAll();
    }
}

