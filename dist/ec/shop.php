<?php
//ファイルの読み込み
require_once "./function/db.php";
//セッション開始
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login/login.php");
    exit;
}

// 条件指定したSQL文をセット
$stmt = $pdo->prepare('SELECT * FROM products');

// SQL実行
$stmt->execute();
$rows=$stmt;



//POST通信だった場合はログイン処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // var_dump($_POST);
    $ps=$_POST;
    var_dump($_POST);
    // while(){

    // }

      foreach ($ps as $key => $value){
        if(intval($value) > 0){
            // $carts += array( );
        }
      }

      
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
<body class="page-shop">
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
            <h1>商品ページ</h1>
           
            <div class=p-simpleBoxs>
                <?php foreach ($rows as $row) { ?>
                <form action="./shop_detail.php" method="GET">
                <div class="c-simpleBox">
                    <?php echo $row['value'] === 0 ? '売り切れ' : '発売中'; ?>
                    <div class="c-simpleBox__img">
                        <img src="./images/product.svg" alt="">
                    </div>
                    <?php echo $row["name"] ?><br>
                    値段 : <?php echo $row["price"] ?><br>
                    残り個数 : <?php echo $row["value"] ?>
                    <input type="hidden" name="name" value="<?php echo $row['name']?>">
                    <input type="hidden" name="value" value="<?php echo $row['value']?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']?>"><br>
                    <input type="submit" class="btn btn-primary" value="商品詳細画面へ" <?php echo $row['value'] === 0 ? 'disabled' : ''; ?>>
                </div>
                </form>
                <?php } ?>

            </div>
            <a href="./index.php" class="btn btn-primary mt-3">TOPへ</a>
        </div>
    </section>
</body>
</html>