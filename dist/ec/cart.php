<?php
//ファイルの読み込み
require_once "./function/db.php";
//セッション開始
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login/login.php");
    exit;
}
if(!isset($_SESSION["cart"])){
    $carts=array();
}else{
    $carts=$_SESSION["cart"];
}
$price=0;

// $_SESSION["cart"]=array();
if(count($carts) === 1){
    $price=$carts[0]["price"];
}else if(count($carts) >= 2){
    // 同じ名前がカートにある場合
    $flg=false;
    for ($i = 0; $i < count($carts); $i++) {
        $price+=($carts[$i]["price"]*$carts[$i]["buyValue"]);
        }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST["empty"])){
        $_SESSION["cart"]=array();
    }else if(isset($_POST["buy"])){

        if(count($carts) === 1){
            $name = $carts[0]["name"];
            $value = (int)$carts[0]["value"]-(int)$carts[0]["buyValue"];

            // (3) SQL作成
            $stmt = $pdo->prepare("UPDATE products SET value = :value WHERE name = :name");

            // (4) 登録するデータをセット
            $stmt->bindParam( ':name', $name, PDO::PARAM_INT);
            $stmt->bindParam( ':value', $value, PDO::PARAM_STR);
      
            // (5) SQL実行
            $res = $stmt->execute();
        }else if(count($carts) >= 2){
            for ($i = 0; $i < count($carts); $i++) {
                $name = $carts[$i]["name"];
                $value = (int)$carts[$i]["value"]-(int)$carts[$i]["buyValue"];

                $stmt = $pdo->prepare("UPDATE products SET value = :value WHERE name = :name");

                $stmt->bindParam( ':name', $name, PDO::PARAM_INT);
                $stmt->bindParam( ':value', $value, PDO::PARAM_STR);

                $res = $stmt->execute();
            }
        }
        $_SESSION["cart"]=array();
        header("location:./buy_thanks.php");
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
            <h1>カート確認</h1>

            <?php if(count($carts) === 0) {?>
                <p>カートは空です。</p>
                <a href="./index.php" class="btn btn-primary mt-3">TOPへ</a>
                    <a href="./shop.php" class="btn btn-primary mt-3">SHOP画面へ</a>
            <?php }else{ ?>
                <form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
                <?php foreach ($carts as $row) { ?>
                <div class="media text-muted pt-3" style="font-size:1.4rem;">
                    <img src="./images/product.svg" alt="" width="100" style="margin-right:24px;">
                    <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                            <?php echo $row["name"] ?><br>
                            値段 : <?php echo $row["price"] ?><br>
                            購入個数 : <?php echo $row["buyValue"] ?>
                            
                    </div>
                </div>
                <?php } ?>
                <div class="text-muted pt-3" style="font-size:1.4rem;">
                    合計金額：<?php echo $price; ?>
                <div>
                    <a href="./index.php" class="btn btn-primary mt-3">TOPへ</a>
                    <a href="./shop.php" class="btn btn-primary mt-3">SHOP画面へ</a>
                    <button class="btn btn-primary mt-3" type="submit" name="buy">購入する</button>
                    <button class="btn btn-primary mt-3" type="submit" name="empty">カートを空にする</button>
                </form>
            <?php } ?>

           
        
        </div>
    </section>
</body>
</html>
                    