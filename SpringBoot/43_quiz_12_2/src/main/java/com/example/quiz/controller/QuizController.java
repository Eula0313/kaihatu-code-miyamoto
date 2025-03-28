package com.example.quiz.controller;

import java.util.Optional;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import com.example.quiz.entity.Quiz;
import com.example.quiz.form.QuizForm;
import com.example.quiz.service.QuizService;

/** Quizコントローラ */
@Controller
@RequestMapping("/quiz")
public class QuizController {

    /** DI */
    @Autowired
    QuizService service;

    /** 「form-backing bean」の初期化 */
    @ModelAttribute
    public QuizForm setUpForm() {
        QuizForm form = new QuizForm();
        // ラジオボタンのデフォルト値設定
        form.setAnswer(true);
        return form;
    }

    /** Quizの一覧を表示します */
    @GetMapping
    public String showList(QuizForm quizForm, Model model) {
        // 新規登録設定
        quizForm.setNewQuiz(true);
        // 一覧を取得する
        Iterable<Quiz> list = service.selectAll();
        // 表示用「Model」への格納
        model.addAttribute("list", list);
        model.addAttribute("title", "登録用フォーム");
        return "crud";
    }

    @PostMapping("/insert")
    public String insert(@Validated QuizForm quizForm, BindingResult bindingResult,
                         Model model, RedirectAttributes redirectAttributes) {

        // FormからEntityへの詰め替え
        Quiz quiz = new Quiz();
        quiz.setQuestion(quizForm.getQuestion());
        quiz.setAnswer(quizForm.getAnswer());
        quiz.setAuthor(quizForm.getAuthor());

        // 入力チェック
        if (!bindingResult.hasErrors()) {
            service.insertQuiz(quiz);
            redirectAttributes.addFlashAttribute("complete", "登録が完了しました");
            return "redirect:/quiz";
        } else {
            // エラーがある場合は、一覧表示処理を呼びます
            return showList(quizForm, model);
        }
    }

}
