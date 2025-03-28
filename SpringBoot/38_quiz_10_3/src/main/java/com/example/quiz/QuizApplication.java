package com.example.quiz;

import com.example.quiz.entity.Quiz;
import com.example.quiz.repository.QuizRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

import java.util.Optional;

@SpringBootApplication
public class QuizApplication {

	public static void main(String[] args) {
		SpringApplication.run(QuizApplication.class, args)
				.getBean(QuizApplication.class).execute();
	}
	@Autowired
	QuizRepository repository;

	private void execute(){
		//登録
		setup();
		//全件取得
		//showList();
		//1件取得
		showOne();
	}
	private void setup(){
		//エンティティ生成
		Quiz quiz1 = new Quiz(null,"「Spring」はフレームワークですか？",true,"登録太郎");
		//登録実行
		quiz1 = repository.save(quiz1);
		//登録確認
		System.out.println("登録したデータは、"+quiz1+"です。");
		Quiz quiz2 = new Quiz(null,"「Spring MVC」はパッチ処理機能を提供しますか？",false,"登録太郎");
		//登録実行
		quiz2 = repository.save(quiz2);
		//登録確認
		System.out.println("登録したデータは、"+quiz2+"です。");
	}
	private void showList(){
		System.out.println("--- 全件取得開始 ---");
		//リポジトリを使用して全権取得を実施、結果を取得
		Iterable<Quiz> quizzes = repository.findAll();
		for(Quiz quiz : quizzes){
			System.out.println(quiz);
		}
		System.out.println("--- 全件取得完了 ---");
	}
	private void showOne(){
		System.out.println("--- 1件取得開始 ---");
		//リポジトリを使用して全権取得を実施、結果を取得

		Optional<Quiz> quizOpt = repository.findById(1);
		if(quizOpt.isPresent()){
			System.out.println(quizOpt.get());
		}else{
			System.out.println("該当する問題が存在しません");
		}
		System.out.println("--- 1件取得完了 ---");
	}
}
