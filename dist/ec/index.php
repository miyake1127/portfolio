<?php
session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>
    <header class="c-header">
        <div class="c-header__inner">
            <div class="c-header__logo">
                簡易ECサイト
            </div>
            <div class="c-header__user">
                <span><?php echo htmlspecialchars($_SESSION["name"]); ?>さん</span>
                <span><a href="./login/logout.php">ログアウト</a></span>     
            </div>
        </div>
    </header>

    <section class="l-section">
        <div class="l-section__inner">
            <h1>簡易ECメインページ</h1>
            <div class=p-simpleBoxs>
                <a href="./addProducts.php" class="c-simpleBox">
                    <div class="c-simpleBox__img">
                        <img src="./images/add.svg" alt="">
                    </div>
                    商品追加ページ
                </a>
                <a href="./shop.php" class="c-simpleBox">
                    <div class="c-simpleBox__img">
                        <img src="./images/shop.svg" alt="">
                    </div>
                    購入ページ
                </a>
                <a href="cart.php" class="c-simpleBox">
                    <div class="c-simpleBox__img">
                        <img src="./images/cart.svg" alt="">
                    </div>
                    カート確認
                </a>
            </div>
        </div>
    </section>
</body>
</html>