package com.example.pokemon.controller;

import com.example.pokemon.dto.PokemonDTO;
import com.example.pokemon.entity.Moves;
import com.example.pokemon.entity.Pokemon;
import com.example.pokemon.entity.Types;
import com.example.pokemon.form.PokemonForm;
import com.example.pokemon.service.MoveService;
import com.example.pokemon.service.PokemonService;
import com.example.pokemon.service.TypeService;
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
@RequestMapping("/pokemon")
public class PokemonController {

    @Autowired
    private PokemonService pokemonService;

    @Autowired
    private TypeService typeService;

    @Autowired
    private MoveService moveService;


    @GetMapping
    public String listPokemon(Model model) {
        //ポケモン一覧を取得
        List<PokemonDTO> pokemon = pokemonService.selectAllPokemon();
        model.addAttribute("pokemon", pokemon);
        model.addAttribute("title", "ポケモン一覧");
        return "pokemon/list";
    }

    //登録画面表示
    @GetMapping("/create")
    public String createPokemonForm(PokemonForm pokemonForm, Model model) {
        pokemonForm.setNewPokemon(true);

        List<Types> types = typeService.getAllTypes();
        List<Moves> moves = (List<Moves>) moveService.selectAllMove();

        model.addAttribute("types", types);
        model.addAttribute("moves", moves);
        model.addAttribute("title", "ポケモン登録");
        return "pokemon/create";
    }

    // 登録処理
    @PostMapping("/create")
    public String createPokemon(@Validated PokemonForm pokemonForm, BindingResult bindingResult,
                                Model model, RedirectAttributes redirectAttributes) {

        if (bindingResult.hasErrors()) {
            List<Types> types = typeService.getAllTypes();
            List<Moves> moves = (List<Moves>) moveService.selectAllMove();

            model.addAttribute("types", types);
            model.addAttribute("moves", moves);
            model.addAttribute("title", "ポケモン登録");
            return "pokemon/create";
        }

        Pokemon pokemon = new Pokemon();
        pokemon.setName(pokemonForm.getName());
        pokemon.setHp(pokemonForm.getHp());
        pokemon.setAttack(pokemonForm.getAttack());
        pokemon.setTypeId(pokemonForm.getTypeId());
        pokemon.setMoveId(pokemonForm.getMoveId());

        pokemonService.insertPokemon(pokemonForm);
        redirectAttributes.addFlashAttribute("complete", "ポケモンを登録しました！");
        return "redirect:/pokemon";
    }

    //更新画面表示
    @GetMapping("/edit/{id}")
    public String editPokemonForm(PokemonForm pokemonForm, @PathVariable Integer id, Model model) {
        Optional<Pokemon> pokemonOpt = pokemonService.selectOnePokemonById(id);

        if (pokemonOpt.isPresent()) {
            Pokemon pokemon = pokemonOpt.get();
            pokemonForm = makePokemonForm(pokemon);
        } else {
            return "redirect:/pokemon";
        }

        List<Types> types = typeService.getAllTypes();
        List<Moves> moves = (List<Moves>) moveService.selectAllMove();

        model.addAttribute("pokemonId", pokemonForm.getPokemonId());
        model.addAttribute("types", types);
        model.addAttribute("moves", moves);
        model.addAttribute("pokemonForm", pokemonForm);
        model.addAttribute("title", "ポケモン編集");
        return "pokemon/edit";
    }

    //更新処理
    @PostMapping("/edit/{id}")
    public String editPokemon(@PathVariable Integer id, @Validated PokemonForm pokemonForm, BindingResult bindingResult, Model model,
                              RedirectAttributes redirectAttributes) {

        if (bindingResult.hasErrors()) {
            List<Types> types = typeService.getAllTypes();
            List<Moves> moves = (List<Moves>) moveService.selectAllMove();

            model.addAttribute("types", types);
            model.addAttribute("moves", moves);
            model.addAttribute("pokemonId", id);
            model.addAttribute("pokemonForm", pokemonForm);
            model.addAttribute("title", "ポケモン編集");
            return "pokemon/edit";
        }

        pokemonService.updatePokemon(id, pokemonForm);
        redirectAttributes.addFlashAttribute("complete", "ポケモンの情報を更新しました！");
        return "redirect:/pokemon";
    }


    //削除処理
    @PostMapping("/delete/{id}")
    public String deletePokemon(@PathVariable Integer id, RedirectAttributes redirectAttributes) {
        try {//削除処理と削除メッセージ
            pokemonService.deletePokemon(id);
            redirectAttributes.addFlashAttribute("complete", "ポケモンを削除しました！");

        } catch (IllegalStateException e) {
            // エラーメッセージを設定
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/pokemon";
    }

    /** トレーナーデータからフォームデータに変換するメソッド */
    private PokemonForm makePokemonForm(Pokemon data) {
        PokemonForm form = new PokemonForm();
        form.setPokemonId(data.getPokemonId());
        form.setName(data.getName());
        form.setHp(data.getHp());
        form.setAttack(data.getAttack());
        form.setTypeId(data.getTypeId());
        form.setMoveId(data.getMoveId());
        return form;
    }
}
