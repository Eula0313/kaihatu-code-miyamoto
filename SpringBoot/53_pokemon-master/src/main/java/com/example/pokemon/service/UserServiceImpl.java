package com.example.pokemon.service;

import com.example.pokemon.dto.UserDTO;
import com.example.pokemon.entity.UserPokemon;
import com.example.pokemon.entity.Users;
import com.example.pokemon.form.UserForm;
import com.example.pokemon.repository.UserPokemonRepository;
import com.example.pokemon.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
public class UserServiceImpl implements UserService {

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private UserPokemonRepository userPokemonRepository;

    @Override
    public List<UserDTO> selectAllUsers() {
        return userRepository.findAllWithDetails();
    }

    @Override
    public Optional<UserDTO> selectOneUserById(Integer id) {
        return userRepository.findById(id).map(user -> {
            UserDTO dto = new UserDTO();
            dto.setUserId(user.getUserId());
            dto.setEmail(user.getEmail());
            dto.setTrainerName(user.getTrainerName());
            dto.setTrainerIcon(user.getTrainerIcon());
            dto.setTrainerLevel(user.getTrainerLevel());

            List<Integer> partnerPokemonIds = getPartnerPokemonIds(user.getUserId());
            dto.setPartnerPokemonIds(partnerPokemonIds);
            dto.setPartnerPokemonName(String.join(", ",
                    partnerPokemonIds.stream().map(String::valueOf).toList()));

            return dto;
        });
    }

    @Override
    public void insertUser(UserForm userForm) {
        Users user = new Users();
        user.setEmail(userForm.getEmail());
        user.setTrainerName(userForm.getTrainerName());
        user.setTrainerIcon(userForm.getTrainerIcon());
        user.setTrainerLevel(userForm.getTrainerLevel());

        if (userForm.getPartnerPokemonIds() != null && userForm.getPartnerPokemonIds().length > 0) {
            user.setPartnerPokemonId(userForm.getPartnerPokemonIds()[0]);
        } else {
            user.setPartnerPokemonId(1);
        }

        Users savedUser = userRepository.save(user);

        // UserPokemonテーブルにポケモンを保存
        if (userForm.getPartnerPokemonIds() != null) {
            for (int i = 0; i < userForm.getPartnerPokemonIds().length; i++) {
                UserPokemon userPokemon = new UserPokemon();
                userPokemon.setUserId(savedUser.getUserId());
                userPokemon.setPokemonId(userForm.getPartnerPokemonIds()[i]);
                userPokemon.setPosition(i + 1);
                userPokemonRepository.save(userPokemon);
            }
        }
    }


    @Override
    public void updateUser(Integer id, UserForm userForm) {
        Users updater = userRepository.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("指定されたユーザーが見つかりません"));

        if (userForm.getTrainerIcon() == null || userForm.getTrainerIcon().isEmpty()) {
            throw new IllegalArgumentException("Trainer Icon は必須です");
        }

        // 基本情報の更新
        updater.setEmail(userForm.getEmail());
        updater.setTrainerName(userForm.getTrainerName());
        updater.setTrainerIcon(userForm.getTrainerIcon());
        updater.setTrainerLevel(userForm.getTrainerLevel());

        // ポケモンデータの更新
        userPokemonRepository.deleteByUserId(id);
        if (userForm.getPartnerPokemonIds() != null && userForm.getPartnerPokemonIds().length > 0) {
            for (int i = 0; i < userForm.getPartnerPokemonIds().length; i++) {
                UserPokemon userPokemon = new UserPokemon();
                userPokemon.setUserId(id);
                userPokemon.setPokemonId(userForm.getPartnerPokemonIds()[i]);
                userPokemon.setPosition(i + 1);
                userPokemonRepository.save(userPokemon);
            }

            // partner_pokemon_idを1つ目のポケモンに設定
            updater.setPartnerPokemonId(userForm.getPartnerPokemonIds()[0]);
        }

        userRepository.save(updater);
    }



    @Override
    public List<Integer> getPartnerPokemonIds(Integer userId) {
        return userPokemonRepository.findPokemonIdsByUserId(userId);
    }

    @Override
    public void deleteUser(Integer id) {
        userPokemonRepository.deleteByUserId(id);
        userRepository.deleteById(id);
    }
}
