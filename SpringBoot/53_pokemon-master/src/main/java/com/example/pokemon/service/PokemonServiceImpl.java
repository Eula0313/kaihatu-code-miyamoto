package com.example.pokemon.service;

import com.example.pokemon.dto.PokemonDTO;
import com.example.pokemon.entity.Moves;
import com.example.pokemon.entity.Pokemon;
import com.example.pokemon.entity.Types;
import com.example.pokemon.form.PokemonForm;
import com.example.pokemon.repository.MoveRepository;
import com.example.pokemon.repository.PokemonRepository;
import com.example.pokemon.repository.TypeRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
public class PokemonServiceImpl implements PokemonService {

    @Autowired
    private PokemonRepository pokemonRepository;

    @Autowired
    private TypeRepository typeRepository;

    @Autowired
    private MoveRepository moveRepository;


    @Override
    public List<PokemonDTO> selectAllPokemon() {
        return pokemonRepository.selectAllPokemon();
    }

    @Override
    public Optional<Pokemon> selectOnePokemonById(Integer id) {
        return pokemonRepository.findById(id);
    }

    @Override
    public void insertPokemon(PokemonForm pokemonForm) {
        Pokemon pokemon = new Pokemon();
        pokemon.setName(pokemonForm.getName());
        pokemon.setHp(pokemonForm.getHp());
        pokemon.setAttack(pokemonForm.getAttack());
        pokemon.setTypeId(pokemonForm.getTypeId());
        pokemon.setMoveId(pokemonForm.getMoveId());
        pokemonRepository.save(pokemon);
    }

    @Override
    public void updatePokemon(Integer id, PokemonForm pokemonForm) {
        Pokemon updatePokemon = pokemonRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("指定されたIDのポケモンが見つかりません: " + id));

        updatePokemon.setName(pokemonForm.getName());
        updatePokemon.setHp(pokemonForm.getHp());
        updatePokemon.setAttack(pokemonForm.getAttack());
        updatePokemon.setTypeId(pokemonForm.getTypeId());
        updatePokemon.setMoveId(pokemonForm.getMoveId());

        pokemonRepository.save(updatePokemon);
    }

    @Override
    public void deletePokemon(Integer id) {
        // 使用されているポケモンかチェック
        if (pokemonRepository.existsReferencedInUserPokemon(id)) {
            throw new IllegalStateException("このポケモンはユーザーに使用されているため削除できません");
        }

        // 削除
        pokemonRepository.deleteById(id);
    }

    @Override
    public List<Pokemon> getAllPokemon() {
        return (List<Pokemon>) pokemonRepository.findAll();
    }

}
