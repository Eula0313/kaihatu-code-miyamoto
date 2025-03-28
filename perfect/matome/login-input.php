<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <title>Login</title>
    <style>
        .error-message {
            color: red;
        }

        #eye-icon {
            width: 30px; 
            height: 30px;
            cursor: pointer;
            position: absolute;
            right: 20px; 
            top: 40%; 
            transform: translate(0, -50%);
        }

        .password-container {
            position: relative;
        }
    </style>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("login-pass");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.src = "./image/ai.png"; 
            } else {
                passwordInput.type = "password";
                eyeIcon.src = "./image/eye.png";
            }
        }

        function validateForm() {
            var loginID = document.getElementById("login-id").value;
            var loginPW = document.getElementById("login-pass").value;

            var errorElement = document.getElementById("error-message");

            errorElement.innerHTML = "";

            if (loginID === "" && loginPW === "") {
                errorElement.innerHTML = "ログインIDとパスワードを入力してください。";
                return false; 
            }

            if (loginID === "") {
                errorElement.innerHTML = "ログインIDを入力してください。";
                return false;
            }

            if (loginPW === "") {
                errorElement.innerHTML = "パスワードを入力してください。";
                return false; 
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="signin">
        <h1>SHINE</h1>
        <form action="login-output.php" method="POST" onsubmit="return validateForm();">
            <class id="LOGIN-ID">
                <span><label for="loginid" id="loginid">login ID</label></span><br>
                <input id="login-id" name="ID" type="text" placeholder="IDを入力"><br>
            </class>
            <class id="LOGIN-PASS">
                <label for="loginpass" id="loginpass">password</label><br>
                <div class="password-container">
                    <input id="login-pass" name="PW" type="password" placeholder="パスワードを入力">
                    <img id="eye-icon" src="./image/ai.png" onclick="togglePassword()" alt="eye-icon" style="cursor: pointer;">
                </div>
            </class>
            <p class="button"><button id="login-button" type="submit">ログイン</button></p>
            <p class="error-message" id="error-message"></p>
            <a href="customer-input.php">新規会員登録</a><br>
        </form>
    </div>
    <div class="ori">
        <svg xmlns="http://www.w3.org/2000/svg" width="1100" height="800" viewBox="0 0 1253 855" fill="none">
            <path
                d="M0.5 34C18.2589 34 28.5791 33.845 33.8536 34M33.8536 34C61.3981 34.8093 -48.6607 44.0714 44.5 128C155.5 228 155.5 188.5 183 128C205 79.6 151.5 23.1667 122 1L33.8536 34ZM1162 654V706.5M1162 759H1250.5L1162 706.5M1162 759V706.5M1162 759L1190 853L1073.5 759L1129 706.5"
                stroke="black" />
        </svg>
    </div>
    <div class="jagaimo-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="163" height="135" viewBox="0 0 163 135" fill="none">
            <path d="M73 134L1 40L50.75 20.75M50.75 20.75L100.5 1.5L73 95.5L161.5 40" stroke="black" /> 
            </svg>
    </div>
    <div class="mimizu">
        <svg xmlns="http://www.w3.org/2000/svg" width="194" height="254" viewBox="0 0 194 254" fill="none">
            <path d="M61.5 139.5L151.5 253.5L1.5 1.5L193.5 49.5" stroke="black" />
        </svg>
    </div>
    <div class="yama">
        <svg xmlns="http://www.w3.org/2000/svg" width="1440" height="619" viewBox="0 0 1440 619" fill="none">
            <path
                d="M1295 385.5L1090.5 258L1411 175.5V618L1184.5 484.999L1295 319V-400L1759.5 -466.5L930 -400H808C-1365.28 -366.001 -920.804 -505.268 747.5 -466.5C2415.8 -427.732 566.249 -452.66 482 -462.839M482 -462.839C471.794 -464.072 462.374 -465.312 454 -466.5L482 -462.839Z"
                stroke="black" />
        </svg>
    </div>
    </form>
</body>

<style>
    p.button{
        margin-top: 30px;
    }
    button{
            width: 200px;
            border: none;
            padding-bottom: 10px;
            padding: 10px 20px;
            cursor: pointer;
            color: #000;
            border: 2px solid #000;
            border-radius: 5px;
            background: #fff;
            font-weight: bold;
   }

    button:hover {
            background-color: rgb(0, 0, 0);
            color: #ffffff;
    }
</style>
<?php require 'footer.php'; ?>
