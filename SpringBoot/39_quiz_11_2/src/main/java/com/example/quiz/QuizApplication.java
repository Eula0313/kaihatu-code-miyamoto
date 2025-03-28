package com.example.quiz;

import com.example.quiz.entity.Quiz;
import com.example.quiz.service.QuizService;
import com.example.quiz.repository.QuizRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Optional;

@SpringBootApplication
public class QuizApplication {

	public static void main(String[] args) {
		/*起動メソッド*/
		SpringApplication.run(QuizApplication.class, args)
				.getBean(QuizApplication.class).execute();
	}

	@Autowired
	QuizService service;

	/*実装メソッド*/
	private void execute() {
		//登録
		setup();
		//全件取得
		//showList();
		//1件取得
		//showOne();
		//更新
		updateQuiz();
		//削除
		deleteQuiz();
		//クイズを実行する
		//doQuiz();
	}

	/*クイズ5件登録*/
	private void setup() {
		//System.out.println("---登録処理開始---");

		// エンティティ生成
		Quiz quiz1 = new Quiz(null, "「Java」はオブジェクト指向言語である。", true, "登録太郎");
		Quiz quiz2 = new Quiz(null, "「Spring Data」はデータアクセスに対する機能を提供する。", true, "登録太郎");
		Quiz quiz3 = new Quiz(null, "プログラムが沢山配置されているサーバーのことを「ライブラリ」という。", false, "登録太郎");
		Quiz quiz4 = new Quiz(null, "「@Component」はインスタンス生成アノテーションである。", true, "登録太郎");
		Quiz quiz5 = new Quiz(null, "「Spring MVC」が実装している「デザインパターン」で、すべてのリクエストを1つのコントローラで受け取るパターンは「シングルコントローラ・パターン」である。", false, "登録太郎");

		// リストにエンティティを格納
		List<Quiz> quizList = new ArrayList<>();
		Collections.addAll(quizList,quiz1, quiz2, quiz3, quiz4, quiz5);

		for (Quiz quiz : quizList) {

			service.insertQuiz(quiz);
		}
	}


	private void showList(){
		System.out.println("---全件取得開始---");
		//リポジトリを取得して全件取得を実施
		Iterable<Quiz> quizzes = service.selectAll();
		for (Quiz quiz:quizzes){
			System.out.println(quiz);
		}
		System.out.println("---全件取得完了---");
	}

	//1件取得
	private void showOne() {
		System.out.println("ーーー1件取得開始ーーー");
		// リポジトリを使用して1件取得を実施、結果を取得（戻り値はOptional）
		Optional<Quiz> quizOpt = service.selectOneById(1);

		// 値存在チェック
		if (quizOpt.isPresent()) {
			System.out.println(quizOpt.get());
		} else {
			System.out.println("該当する問題が存在しません・・・");
		}

		System.out.println("ーーー1件取得完了ーーー");
	}

	//更新処理
	private void updateQuiz() {
		System.out.println("ーーー更新処理開始ーーー");
		// 変更したいエンティティを生成する
		Quiz quiz1 = new Quiz(1, "「スプリング」はフレームワークですか？", true, "変更タロウ");
		// 更新実行
		service.updateQuiz(quiz1);
		System.out.println("ーーー更新処理完了ーーー");
	}

	//削除処理
	private void deleteQuiz() {
		System.out.println("ーーー削除処理開始ーーー");
		// 削除実行
		service.deleteQuizById(2);
		System.out.println("ーーー削除処理完了ーーー");
	}


	private void doQuiz () {
		System.out.println("---クイズ1件取得開始---");
		// リポジトリを使用して1件取得を実施、結果を取得（戻り値はOptional）
		Optional<Quiz> quizOpt = service.selectOneRandomQuiz();

		if (quizOpt.isPresent()) {
			System.out.println(quizOpt.get());
		} else {
			System.out.println("該当する問題が存在しません・・・");
		}
		System.out.println("---クイズ1件取得完了---");
		//解答を実施
		Boolean myAnswer = false;
		Integer id = quizOpt.get().getId();
		if (service.checkQuiz(id, myAnswer)) {
			System.out.println("正解です！");
		} else {
			System.out.println("不正解です・・・");
		}
	}
}
