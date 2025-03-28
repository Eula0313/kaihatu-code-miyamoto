package com.example.pokemon.controller;

import com.example.pokemon.dto.UserDTO;
import com.example.pokemon.form.UserForm;
import com.example.pokemon.service.PokemonService;
import com.example.pokemon.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;
import java.util.Optional;

@Controller
@RequestMapping("/users")
public class UserController {

    @Autowired
    private UserService userService;

    @Autowired
    private PokemonService pokemonService;

    // ユーザー一覧表示
    @GetMapping
    public String listUsers(Model model) {
        List<UserDTO> users = userService.selectAllUsers();
        model.addAttribute("users", users);
        model.addAttribute("title", "ユーザー一覧");
        return "users/list";
    }

    // 登録画面表示
    @GetMapping("/create")
    public String createUserForm(Model model) {
        UserForm userForm = new UserForm();
        userForm.setNewUser(true);
        userForm.setPartnerPokemonIds(new Integer[3]);

        model.addAttribute("userForm", userForm);
        model.addAttribute("pokemons", pokemonService.getAllPokemon());
        return "users/create";
    }

    // 登録処理
    @PostMapping("/create")
    public String createUser(@Validated @ModelAttribute UserForm userForm, BindingResult bindingResult,
                             RedirectAttributes redirectAttributes, Model model) {

        if (bindingResult.hasErrors()) {
            model.addAttribute("pokemons", pokemonService.getAllPokemon());
            return "users/create";
        }

        // partnerPokemonIdsが空の場合、デフォルト値を設定
        if (userForm.getPartnerPokemonIds() == null || userForm.getPartnerPokemonIds().length == 0) {
            userForm.setPartnerPokemonIds(new Integer[]{1}); // デフォルトのポケモンID（例: 1）
        }

        userService.insertUser(userForm);
        redirectAttributes.addFlashAttribute("complete", "ユーザーを登録しました！");
        return "redirect:/users";
    }


    // 更新画面表示
    @GetMapping("/edit/{id}")
    public String editUserForm(@PathVariable Integer id, Model model,
                               RedirectAttributes redirectAttributes) {

        Optional<UserDTO> userOpt = userService.selectOneUserById(id);
        if (userOpt.isEmpty()) {
            redirectAttributes.addFlashAttribute("error", "ユーザーが見つかりませんでした");
            return "redirect:/users";
        }

        UserForm userForm = makeUserForm(userOpt.get());
        model.addAttribute("userId", id);
        model.addAttribute("userForm", userForm);
        model.addAttribute("pokemons", pokemonService.getAllPokemon());
        return "users/edit";
    }

    // 更新処理
    @PostMapping("/edit/{id}")
    public String editUser(@PathVariable Integer id, @Validated @ModelAttribute UserForm userForm,
                           BindingResult bindingResult, RedirectAttributes redirectAttributes, Model model) {

        if (bindingResult.hasErrors()) {

            model.addAttribute("userId", id);
            model.addAttribute("pokemons", pokemonService.getAllPokemon());
            return "users/edit";
        }

        userService.updateUser(id, userForm);
        redirectAttributes.addFlashAttribute("complete", "ユーザー情報を更新しました！");

        return "redirect:/users";
    }

    // 削除処理
    @PostMapping("/delete/{id}")
    public String deleteUser(@PathVariable Integer id,
                             RedirectAttributes redirectAttributes) {
        try {
            userService.deleteUser(id);
            redirectAttributes.addFlashAttribute("complete", "ユーザーを削除しました！");
        } catch (IllegalStateException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/users";
    }

    // UserDTO から UserForm に変換
    private UserForm makeUserForm(UserDTO data) {
        UserForm form = new UserForm();
        form.setUserId(data.getUserId());
        form.setEmail(data.getEmail());
        form.setTrainerName(data.getTrainerName());
        form.setTrainerIcon(data.getTrainerIcon());
        form.setTrainerLevel(data.getTrainerLevel());
        if (data.getPartnerPokemonIds() != null && !data.getPartnerPokemonIds().isEmpty()) {
            form.setPartnerPokemonIds(data.getPartnerPokemonIds().toArray(new Integer[0]));
        } else {
            form.setPartnerPokemonIds(new Integer[3]); // 空のスロット
        }
        return form;
    }
}