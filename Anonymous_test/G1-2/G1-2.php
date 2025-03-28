<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="bootstrap.min.css" rel="stylesheet">
    <title>Anonymous</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 0.25em;
        }
    </style>
</head>
<body>
    <div class="background-container">
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1231630/moon2.png" alt="">
        <div class="stars"></div>
        <div class="twinkling"></div>
        <div class="clouds"></div>
 
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="G1-2.php" class="">
                                <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i></h3>
                            </a>
                            <h3>Anonymousへようこそ</h3>
                        </div>
                        <p class="text-center mb-0">ルームに入る前に、あなたのニックネームを設定してください</p>
                        <form action="create_dynamic_url.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingText" maxlength="8" name="nickname" placeholder="jhondoe">
                                <label for="floatingText">ニックネームを入力してください。</label>
                                <?php if (!empty($_SESSION['error_message'])) { ?>
                                    <div class="error-message"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                                <?php } ?>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">ルームを作成する</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>