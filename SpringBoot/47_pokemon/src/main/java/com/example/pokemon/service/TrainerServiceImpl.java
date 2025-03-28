package com.example.pokemon.service;

import com.example.pokemon.entity.Trainer;
import com.example.pokemon.repository.TrainerRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

@Service
@Transactional
public class TrainerServiceImpl implements TrainerService {

    @Autowired
    private TrainerRepository repository;

    /** トレーナー情報を全件取得します */
    @Override
    public Iterable<Trainer> selectAll() {
        return repository.findAll();
    }

    /** トレーナー情報を、IDをキーに1件取得します */
    @Override
    public Optional<Trainer> selectOneById(Integer id) {
        return repository.findById(id);
    }

    /** トレーナー情報を登録します */
    @Override
    public void insertTrainer(Trainer trainer) {
        repository.save(trainer);
    }

    /** トレーナー情報を更新します */
    @Override
    public void updateTrainer(Trainer trainer) {
        repository.save(trainer);
    }

    /** トレーナー情報を削除します */
    @Override
    public void deleteTrainerById(Integer id) {
        repository.deleteById(id);
    }
}
