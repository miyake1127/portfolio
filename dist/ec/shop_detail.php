<?php
//ファイルの読み込み
require_once "./function/db.php";
//セッション開始
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login/login.php");
    exit;
}

$row=array();

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $row=$_GET;
    // if($_SESSION["cart"])
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // var_dump($_POST);
    
    if(isset($_SESSION["cart"])){
        
        if(count($_SESSION["cart"]) === 0){
            $_SESSION["cart"][]=$_POST;
        }else{
            // 同じ名前がカートにある場合
            $flg=false;
            for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
                if($_SESSION["cart"][$i]["name"] === $_POST["name"]){
                    $_SESSION["cart"][$i]["buyValue"]=$_POST["buyValue"];
                    $flg=true;
                    break;
                }
            }

            if(!$flg){
                $_SESSION["cart"][]=$_POST;
            }
        }

    }else{
        $_SESSION["cart"]=array();
        $_SESSION["cart"][]=$_POST;
    }
    
    header("location:./cart.php");
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
            <h1>商品ページ詳細ページ</h1>
            <form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
            <div class="media text-muted pt-3" style="font-size:1.4rem;">
                <img src="./images/product.svg" alt="" width="200" style="margin-right:24px;">
                <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                         <?php echo $row["name"] ?><br>
                        値段 : <?php echo $row["price"] ?><br>
                        在庫 : <?php echo $row["value"] ?><br><br>
                        
                        <input type="number" name="buyValue" class="form-control" min="1" max="<?php echo $row["value"] ?>" value="1"><br>

                        <input type="submit" class="btn btn-primary" value="カートに入れる">
                        <a href="./shop.php" class="btn btn-primary">shopへ</a>

                        <input type="hidden" name="name" value="<?php echo $row['name']?>">
                        <input type="hidden" name="value" value="<?php echo $row['value']?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']?>"><br>
                </div>
            </div>
            </form>
        
        </div>
    </section>

</body>
</html>
                    