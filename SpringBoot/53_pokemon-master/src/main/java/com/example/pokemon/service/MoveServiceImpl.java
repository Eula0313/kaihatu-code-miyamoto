package com.example.pokemon.service;

import com.example.pokemon.entity.Moves;
import com.example.pokemon.form.MoveForm;
import com.example.pokemon.repository.MoveRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

@Service
@Transactional
public class MoveServiceImpl implements MoveService {
    @Autowired
    private MoveRepository moveRepository;

    @Override
    public Iterable<Moves> selectAllMove() {
        return moveRepository.findAll();
    }

    @Override
    public Optional<Moves> selectOneMoveById(Integer id) {
        return moveRepository.findById(id);

    }

    @Override
    public void insertMove(MoveForm moveForm) {
        Moves move = new Moves();
        move.setName(moveForm.getName());
        move.setPower(moveForm.getPower());
        moveRepository.save(move);
    }

    @Override
    public void updateMove(Integer id, MoveForm moveForm) {
        Moves updateMove = moveRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("指定されたIDの技が見つかりません: " + id));

        updateMove.setName(moveForm.getName());
        updateMove.setPower(moveForm.getPower());

        moveRepository.save(updateMove);
    }

    @Override
    public void deleteMove(Integer id) {
        //使用されている技かチェック
        if (moveRepository.existsReferencedInPokemon(id)) {
            throw new IllegalStateException("この技はポケモンに使用されているため削除できません");
        }

        //削除
        moveRepository.deleteById(id);
    }
}
