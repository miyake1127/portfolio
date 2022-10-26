<?php
//ファイルの読み込み
require_once "./function/db.php";
require_once "./function/func.php";
//セッション開始
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login/login.php");
    exit;
}

//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'name'  => '',
    'price'  => 1,
    'value'  => 1
];

//POST通信だった場合はログイン処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // POSTされてきたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    $errors = array();

    if(empty($datas['name'])){
        $errors['name']='商品名を入力してください。';
    }

        //データベースの中に同一ユーザー名が存在していないか確認
    if(empty($errors['name'])){
            $sql = "SELECT id FROM products WHERE name = :name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue('name',$datas['name'],PDO::PARAM_INT);
            $stmt->execute();
            if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $errors['name'] = 'その商品名は既に登録されています。';
            }
    }
    //エラーがなかったらDBへの新規登録を実行
    if(empty($errors)){
        $params = [
            'id' =>null,
            'name'=>$datas['name'],
            'price'=>$datas['price'],
            'value'=>$datas['value']
        ];

        $count = 0;
        $value = '';
        $values = '';
        foreach (array_keys($params) as $key) {
            if($count > 0){
                $columns .= ',';
                $values .= ',';
            }
            $columns .= $key;
            $values .= ':'.$key;
            $count++;
        }

        $pdo->beginTransaction();//トランザクション処理
        try {
            $sql = 'insert into products ('.$columns .')values('.$values.')';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pdo->commit();
            header("location: thanksProducts.php");
            exit;
        } catch (PDOException $e) {
            echo 'ERROR: Could not register.';
            $pdo->rollBack();
        }
    }

}



?>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            font: 14px sans-serif;
        }
        .wrapper{
            width: 400px;
            padding: 20px;
            margin: 0 auto;
        }
    </style>
    
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
<div class="wrapper">
<form action="<?php echo $_SERVER ['SCRIPT_NAME']; ?>" method="post">
            <div class="form-group">
                <label>商品名</label>
                <input type="text" name="name" class="form-control <?php echo (!empty(h($errors['name']))) ? 'is-invalid' : ''; ?>" value="<?php echo $datas['name']; ?>">
                <span class="invalid-feedback"><?php echo h($errors['name']); ?></span>
            </div>    
            <div class="form-group">
                <label>値段</label>
                <input type="number" name="price" class="form-control" min="1" value="1">
                <span class="invalid-feedback"><?php echo h($errors['price']); ?></span>
            </div>
            <div class="form-group">
                <label>個数</label>
                <input type="number" name="value" class="form-control" min="1" max="500" value="1">
                <span class="invalid-feedback"><?php echo h($errors['value']); ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="登録">
            </div>
        </form>
    </div>
</body>