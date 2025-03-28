package com.example.demo.controller;

import com.example.demo.form.UserForm;
import jakarta.validation.ConstraintViolation;
import jakarta.validation.Validation;
import jakarta.validation.Validator;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;

import java.net.InetAddress;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import java.net.InetAddress;
import java.net.UnknownHostException;

@Controller
public class RegistrationTestController {

    private final Validator validator;
    private List<TestResult> testResults;  // 以前の結果を保持するリスト

    public RegistrationTestController() {
        this.validator = Validation.buildDefaultValidatorFactory().getValidator();
        this.testResults = createTestItems();  // 初期化
    }

    @GetMapping("/")
    public String showTestPage(Model model) {

        model.addAttribute("testResults", testResults);
        return "index";
    }

    @PostMapping("/")
    public String executeTest(@RequestParam("fieldName") String fieldName, Model model) {
        // 以前の結果を維持したまま、新たなテスト結果を反映
        for (TestResult testResult : testResults) {
            if (testResult.getFieldName().equals(fieldName)) {
                UserForm userForm = new UserForm();
                populateUserForm(userForm, fieldName, testResult.getTestCase());

                // 指定されたフィールドに対してバリデーションを実行
                Set<ConstraintViolation<UserForm>> violations = validator.validateProperty(userForm, fieldName);
                testResult.setResult(violations.isEmpty() ? "NG" : "OK");
            }
        }

        model.addAttribute("testResults", testResults);

        // ホスト名を取得してモデルに追加
        try {
            String hostName = InetAddress.getLocalHost().getHostName();
            model.addAttribute("hostName", hostName);
        } catch (UnknownHostException e) {
            // エラー発生時のメッセージを追加
            model.addAttribute("hostName", "ホスト名取得に失敗しました: " + e.getMessage());
        }

        return "index";
    }

    // テストデータの設定
    private void populateUserForm(UserForm userForm, String fieldName, String testCase) {

        // テストケースに応じて特定のフィールドの値を変更
        switch (fieldName) {
            case "username":
                if (testCase.equals("空文字")) userForm.setUsername(""); // 空文字ケース
                if (testCase.equals("スペースのみ")) userForm.setUsername(" "); // スペースのみケース
                if (testCase.equals("無効な長さ1")) userForm.setUsername("ab"); // 5文字未満
                if (testCase.equals("無効な長さ2")) userForm.setUsername("oslechitolaksdjf"); // 15文字以下
                if (testCase.equals("特殊文字1")) userForm.setUsername("Invalid@User"); // アットマーク
                if (testCase.equals("特殊文字2")) userForm.setUsername("Invalid!User"); // 感嘆符
                if (testCase.equals("特殊文字3")) userForm.setUsername("Invalid*User"); // アスタリスク
                if (testCase.equals("スペース")) userForm.setUsername("Invalid User"); // スペース
                if (testCase.equals("タブ")) userForm.setUsername("Invalid\tUser"); // タブ
                if (testCase.equals("改行")) userForm.setUsername("Invalid\nUser"); // 改行
                if (testCase.equals("日本語")) userForm.setUsername("ユーザー名"); // 日本語
                if (testCase.equals("非ラテン文字")) userForm.setUsername("InvalidñUser"); // 非ラテン文字
                if (testCase.equals("ピリオド")) userForm.setUsername("Invalid.User"); // ピリオド
                if (testCase.equals("アンダースコア")) userForm.setUsername("Invalid_User"); // アンダースコア
                if (testCase.equals("複合1")) userForm.setUsername("Invalid!User 123"); // 特殊文字とスペース
                if (testCase.equals("複合2")) userForm.setUsername("Invalid@ñUser"); // 特殊文字と非ラテン文字
                break;
            case "email":
                if (testCase.equals("空白")) userForm.setEmail(""); // 空白ケース
                if (testCase.equals("無効なフォーマット")) userForm.setEmail("invalid-email"); // フォーマットエラー
                if (testCase.equals("「@」が欠けている")) userForm.setEmail("userexample.com"); // 「@」がない
                if (testCase.equals("ドメイン部分が欠けている1")) userForm.setEmail("user@.com"); // ドメイン名がない
                if (testCase.equals("ドメイン部分が欠けている2")) userForm.setEmail("user@"); // ドメインが全くない
                if (testCase.equals("ドットが欠けている")) userForm.setEmail("user@com"); // トップレベルドメインにドットがない
                if (testCase.equals("ドットが誤った場所にある")) userForm.setEmail("user@.com"); // ドットの前に何もない
                if (testCase.equals("ドメイン名が無効（ドットがない）")) userForm.setEmail("user@domaincom"); // ドットがない
                if (testCase.equals("スペースが含まれている1")) userForm.setEmail("user @example.com"); // 「@」の前にスペース
                if (testCase.equals("スペースが含まれている2")) userForm.setEmail("user@ example.com"); // 「@」の後にスペース
                if (testCase.equals("スペースが含まれている3")) userForm.setEmail(" user@example.com"); // メールアドレスの前にスペース
                if (testCase.equals("特殊文字が含まれている1")) userForm.setEmail("user!@example.com"); // 感嘆符「!」が含まれている
                if (testCase.equals("特殊文字が含まれている2")) userForm.setEmail("user@example!.com"); // ドメイン部分に感嘆符「!」が含まれている
                if (testCase.equals("カンマが含まれている")) userForm.setEmail("user@domain,com"); // カンマ「,」が含まれている
                if (testCase.equals("ドメインが短すぎる")) userForm.setEmail("user@example.c"); // トップレベルドメインが1文字
                if (testCase.equals("ドメインのハイフンが無効")) userForm.setEmail("user@-example.com"); // ドメインがハイフンで始まっている
                if (testCase.equals("連続ドットが含まれている")) userForm.setEmail("user@domain..com"); // ドットが連続している
                break;
            case "password":
                // パスワードのバリデーションに引っかかるテストケース
                if (testCase.equals("英字が含まれていない")) userForm.setPassword("12345678!"); // 数字と特殊文字のみ
                if (testCase.equals("数字が含まれていない")) userForm.setPassword("Password!"); // 英字と特殊文字のみ
                if (testCase.equals("特殊文字が含まれていない")) userForm.setPassword("Password123"); // 英字と数字のみ
                if (testCase.equals("8文字未満")) userForm.setPassword("Pass1!"); // 8文字未満
                if (testCase.equals("20文字以下")) userForm.setPassword("Password1234567890!@Q"); // 20文字超過
                if (testCase.equals("英字のみ")) userForm.setPassword("Password"); // 英字のみ
                if (testCase.equals("数字のみ")) userForm.setPassword("12345678"); // 数字のみ
                if (testCase.equals("特殊文字のみ")) userForm.setPassword("!@#$%^&*"); // 特殊文字のみ
                if (testCase.equals("空白が含まれている")) userForm.setPassword("Pass word123!"); // パスワードに空白が含まれている
                break;
            case "age":
                if (testCase.equals("18歳未満")) userForm.setAge(17); // 18歳未満
                if (testCase.equals("20歳以下")) userForm.setAge(21); // 20歳以下
                if (testCase.equals("空白")) userForm.setAge(null); // 空白
                break;
            case "birthDate":
                if (testCase.equals("未来の日付")) userForm.setBirthDate(LocalDate.now().plusDays(1)); // 未来の日付
                if (testCase.equals("空白")) userForm.setBirthDate(null); // 空白
                break;
            case "termsAccepted":
                if (testCase.equals("未同意")) userForm.setTermsAccepted(false); // 同意していない
                break;
        }
    }

    // テスト項目の作成
    private List<TestResult> createTestItems() {
        List<TestResult> testItems = new ArrayList<>();
        testItems.add(new TestResult("ユーザー名", "必須チェック", "未テスト", "username", "空文字","★"));
        testItems.add(new TestResult("ユーザー名", "必須チェック", "未テスト", "username", "スペースのみ","★"));
        testItems.add(new TestResult("ユーザー名", "長さチェック（5文字以上）", "未テスト", "username", "無効な長さ1","★"));
        testItems.add(new TestResult("ユーザー名", "長さチェック（15文字以下）", "未テスト", "username", "無効な長さ2","★"));
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "特殊文字1","★★")); // アットマーク
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "特殊文字2","★★")); // 感嘆符
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "特殊文字3","★★")); // アスタリスク
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "スペース","★★")); // スペース
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "タブ","★★")); // タブ
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "改行","★★")); // 改行
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "日本語","★★")); // 日本語
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "非ラテン文字","★★")); // 非ラテン文字
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "ピリオド","★★")); // ピリオド
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "アンダースコア","★★")); // アンダースコア
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "複合1","★★")); // 特殊文字とスペース
        testItems.add(new TestResult("ユーザー名", "英数字のみチェック", "未テスト", "username", "複合2","★★")); // 特殊文字と非ラテン文字



        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "無効なフォーマット","★★"));
        testItems.add(new TestResult("メールアドレス", "必須チェック", "未テスト", "email", "空白","★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "「@」が欠けている","★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "ドメイン部分が欠けている1","★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "ドメイン部分が欠けている2","★★"));
        testItems.add(new TestResult("メールアドレス", "user@com", "未テスト", "email", "ドットが欠けている","★★★★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "ドットが誤った場所にある","★★"));
        testItems.add(new TestResult("メールアドレス", "user@domaincom", "未テスト", "email", "ドメイン名が無効（ドットがない）","★★★★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "スペースが含まれている1","★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "スペースが含まれている2","★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "スペースが含まれている3","★★"));
        testItems.add(new TestResult("メールアドレス", "user!@example.com", "未テスト", "email", "特殊文字が含まれている1","★★★★★"));
        testItems.add(new TestResult("メールアドレス", "user@example!.com", "未テスト", "email", "特殊文字が含まれている2","★★★★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "カンマが含まれている","★★"));
        testItems.add(new TestResult("メールアドレス", "user@example.c", "未テスト", "email", "ドメインが短すぎる","★★★★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "ドメインのハイフンが無効","★★"));
        testItems.add(new TestResult("メールアドレス", "フォーマットチェック", "未テスト", "email", "連続ドットが含まれている","★★ "));


        // パスワードのテストケースを追加
        // パスワードのバリデーションに引っかかるテストケースを追加
        testItems.add(new TestResult("パスワード", "英字が含まれていないチェック", "未テスト", "password", "英字が含まれていない","★"));
        testItems.add(new TestResult("パスワード", "数字が含まれていないチェック", "未テスト", "password", "数字が含まれていない","★"));
        testItems.add(new TestResult("パスワード", "特殊文字が含まれていないチェック", "未テスト", "password", "特殊文字が含まれていない","★★★★★"));
        testItems.add(new TestResult("パスワード", "8文字未満チェック", "未テスト", "password", "8文字未満","★★"));
        testItems.add(new TestResult("パスワード", "20文字以下チェック", "未テスト", "password", "20文字以下","★★"));
        testItems.add(new TestResult("パスワード", "英字のみチェック", "未テスト", "password", "英字のみ","★★★★★"));
        testItems.add(new TestResult("パスワード", "数字のみチェック", "未テスト", "password", "数字のみ","★★★★★"));
        testItems.add(new TestResult("パスワード", "特殊文字のみチェック", "未テスト", "password", "特殊文字のみ","★★★★★"));
        testItems.add(new TestResult("パスワード", "空白が含まれているチェック", "未テスト", "password", "空白が含まれている","★★★★★"));

        testItems.add(new TestResult("年齢", "18歳以上チェック", "未テスト", "age", "18歳未満","★"));
        testItems.add(new TestResult("年齢", "必須チェック", "未テスト", "age", "空白","★"));
        testItems.add(new TestResult("年齢", "20歳以下チェック", "未テスト", "age", "20歳以下","★"));

        testItems.add(new TestResult("生年月日", "過去の日付チェック", "未テスト", "birthDate", "未来の日付","★★★"));
        testItems.add(new TestResult("生年月日", "必須チェック", "未テスト", "birthDate", "空白","★"));

        testItems.add(new TestResult("利用規約同意", "必須チェック", "未テスト", "termsAccepted", "未同意","★★★"));

        return testItems;
    }

    public static class TestResult {
        private String itemName;
        private String testContent;
        private String result;
        private String fieldName;
        private String testCase;
        private String level;

        public TestResult(String itemName, String testContent, String result, String fieldName, String testCase, String level) {
            this.itemName = itemName;
            this.testContent = testContent;
            this.result = result;
            this.fieldName = fieldName;
            this.testCase = testCase;
            this.level = level;
        }

        public String getItemName() {
            return itemName;
        }

        public String getTestContent() {
            return testContent;
        }

        public String getResult() {
            return result;
        }

        public void setResult(String result) {
            this.result = result;
        }

        public String getFieldName() {
            return fieldName;
        }

        public String getTestCase() {
            return testCase;
        }
        public String getLevel() {
            return level;
        }

    }
}
