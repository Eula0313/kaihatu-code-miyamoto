package com.example.pokemon.controller;

import com.example.pokemon.entity.Trainer;
import com.example.pokemon.form.TrainerForm;
import com.example.pokemon.service.TrainerService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Optional;

@Controller
public class PokemonController {

    @Autowired
    private TrainerService service;

    /** トレーナー登録フォームを初期化するメソッド */
    @ModelAttribute
    public TrainerForm setUpForm() {
        return new TrainerForm();
    }

    /** メニュー画面 */
    @GetMapping("/menu")
    public String menu(Model model) {
        return "menu";
    }

    /** トレーナー一覧表示 */
    @GetMapping("/list")
    public String list(Model model) {
        Iterable<Trainer> trainers = service.selectAll();
        model.addAttribute("trainers", trainers);
        model.addAttribute("title", "トレーナー一覧");
        return "trainers-list";
    }

    /** トレーナー作成画面 */
    @GetMapping("/trainer_create")
    public String insert(TrainerForm trainerForm, Model model) {
        trainerForm.setNewTrainer(true);
        model.addAttribute("title", "トレーナー登録");
        return "insertform";
    }

    /** トレーナー情報を登録する */
    @PostMapping("/insert")
    public String oneDataInsert(@Validated TrainerForm trainerForm, BindingResult bindingResult,
                                RedirectAttributes redirectAttributes) {
        if (bindingResult.hasErrors()) {
            return "insertform";
        }

        Trainer trainer = new Trainer();
        trainer.setTrainerName(trainerForm.getTrainerName());
        trainer.setTrainerLevel(trainerForm.getTrainerLevel());
        trainer.setTrainerIconId(trainerForm.getTrainerIconId());
        trainer.setPartnerId(trainerForm.getPartnerId());

        service.insertTrainer(trainer);
        redirectAttributes.addFlashAttribute("complete", "トレーナーを登録しました！");
        return "redirect:/list";
    }

    /**
     * トレーナー情報の更新画面を表示するメソッド
     */
    @GetMapping("/{trainer_id}")
    public String showUpdate(TrainerForm trainerForm,
                             @PathVariable Integer trainer_id,
                             Model model) {
        Optional<Trainer> trainersOpt = service.selectOneById(trainer_id);

        // 検索したデータをフォームクラスに詰め替え
        if (trainersOpt.isPresent()) {
            Trainer trainer = trainersOpt.get();
            trainerForm = makeTrainerForm(trainer);
        } else {
            return "redirect:/list";
        }

        model.addAttribute("trainer_id", trainerForm.getTrainerId());
        model.addAttribute("trainerForm", trainerForm);
        model.addAttribute("complete", "トレーナー情報更新");
        return "updateform";
    }

    /** トレーナー情報を更新する */
    @PostMapping("/update")
    public String update(@Validated TrainerForm trainerForm, BindingResult bindingResult,
                         RedirectAttributes redirectAttributes) {
        if (bindingResult.hasErrors()) {
            return "updateform";
        }

        Trainer trainer = new Trainer();
        trainer.setTrainerId(trainerForm.getTrainerId());
        trainer.setTrainerName(trainerForm.getTrainerName());
        trainer.setTrainerLevel(trainerForm.getTrainerLevel());
        trainer.setTrainerIconId(trainerForm.getTrainerIconId());
        trainer.setPartnerId(trainerForm.getPartnerId());

        service.updateTrainer(trainer);
        redirectAttributes.addFlashAttribute("complete", "トレーナー情報を更新しました！");
        return "redirect:/list";
    }

    /** トレーナー情報を削除する */
    @PostMapping("/delete")
    public String deleteOneData(@RequestParam("trainerId") Integer trainerId,
                                RedirectAttributes redirectAttributes) {
        service.deleteTrainerById(trainerId);
        redirectAttributes.addFlashAttribute("complete", "トレーナーを削除しました！");
        return "redirect:/list";
    }

    /** トレーナー詳細画面 */
    @GetMapping("/trainer-detail")
    public String detail(Model model) {
        model.addAttribute("title", "トレーナー詳細");
        return "trainer-detail";
    }

    /** ポケモンバトル画面 */
    @GetMapping("/pokemon-battle")
    public String battle(Model model) {
        model.addAttribute("title", "ポケモンバトル");
        return "pokemon-battle";
    }


    /** トレーナーデータからフォームデータに変換するメソッド */
    private TrainerForm makeTrainerForm(Trainer data) {
        TrainerForm form = new TrainerForm();
        form.setTrainerId(data.getTrainerId());
        form.setTrainerName(data.getTrainerName());
        form.setTrainerLevel(data.getTrainerLevel());
        form.setTrainerIconId(data.getTrainerIconId());
        form.setPartnerId(data.getPartnerId());
        return form;
    }
}
