package com.example.pokemon.controller;

import com.example.pokemon.entity.Moves;
import com.example.pokemon.form.MoveForm;
import com.example.pokemon.service.MoveService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Optional;

@Controller
@RequestMapping("/moves")
public class MoveController {

    @Autowired
    private MoveService moveService;

    @GetMapping
    public String listMoves(Model model) {
        //技一覧を取得
        Iterable<Moves> moves = moveService.selectAllMove();
        model.addAttribute("moves", moves);
        model.addAttribute("title", "技一覧");
        return "move/list";
    }

    //登録画面表示
    @GetMapping("/create")
    public String createMoveForm(MoveForm moveForm, Model model) {
        moveForm.setNewMove(true);
        model.addAttribute("title", "技登録");
        return "move/create";
    }

    //登録処理
    @PostMapping("/create")
    public String createMove(@Validated MoveForm moveForm, BindingResult bindingResult,
                             RedirectAttributes redirectAttributes) {
        if (bindingResult.hasErrors()) {
            return "move/create";
        }

        Moves moves = new Moves();
        moves.setName(moveForm.getName());
        moves.setPower(moveForm.getPower());

        moveService.insertMove(moveForm);
        redirectAttributes.addFlashAttribute("complete", "技を登録しました！");
        return "redirect:/moves";
    }

    //更新画面表示
    @GetMapping("/edit/{id}")
    public String editMoveForm(MoveForm moveForm,
                               @PathVariable Integer id,
                               Model model) {
        Optional<Moves> movesOpt = moveService.selectOneMoveById(id);

        if (movesOpt.isPresent()) {
            Moves moves = movesOpt.get();
            moveForm = makeMoveForm(moves);
        } else {
            return "redirect:/moves";
        }
        model.addAttribute("moveId", moveForm.getMoveId());
        model.addAttribute("moveForm", moveForm);
        model.addAttribute("complete", "技情報更新");
        
        return "move/edit";
    }

    //更新処理
    @PostMapping("/edit/{id}")
    public String editMove(@PathVariable Integer id,@Validated MoveForm moveForm, BindingResult bindingResult,
                           Model model,RedirectAttributes redirectAttributes) {

        if (bindingResult.hasErrors()) {

            model.addAttribute("moveId", id);
            model.addAttribute("moveForm", moveForm);
            return "move/edit";
        }

        moveService.updateMove(id, moveForm);
        redirectAttributes.addFlashAttribute("complete", "技の情報を更新しました！");
        return "redirect:/moves";
    }

    //削除処理
    @PostMapping("/delete/{id}")
    public String deleteMove(@PathVariable Integer id, RedirectAttributes redirectAttributes) {
        try {//削除処理と削除メッセージ
            moveService.deleteMove(id);
            redirectAttributes.addFlashAttribute("complete", "技を削除しました！");

        } catch (IllegalStateException e) {
            // エラーメッセージを設定
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/moves";
    }

    /** トレーナーデータからフォームデータに変換するメソッド */
    private MoveForm makeMoveForm(Moves data) {
        MoveForm form = new MoveForm();
        form.setMoveId(data.getMoveId());
        form.setName(data.getName());
        form.setPower(data.getPower());
        return form;
    }
}
