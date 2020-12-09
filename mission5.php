<!--PHP箇所-->
<?php
    //テーブルの作成
    //DB接続設定
    $dsn = 'データベース名';
	$user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブルがないときに、エラーを吐かないようにする
    $sql = "CREATE TABLE IF NOT EXISTS tb_mission5"
    //データベースにID、名前、コメント、日付とパスワードの項目を記載するテーブルを作成
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,".
    "name TEXT,".
    "comment TEXT,".
    "day TEXT,".
    "passward char(32)".
    ")";
    $stmt = $pdo->query($sql);

    //初期の名前とコメントを設定
    $format_name = "名前";
    $format_comment = "コメント";

    //$edit_numberの信号をnullにする。
    $edit_number = 0;

    //投稿、編集、削除内容をデータベースに記載し、下部で表示させる。
    //新規or編集投稿
    if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['passward'])){
        //新規投稿用パスワードの確認
        if($_POST['passward'] == 'pass'){
            //追加するテーブルの項目を準備
            $sql = $pdo -> prepare("INSERT INTO tb_mission5 (name, comment, day, passward) VALUES (:name, :comment, :day, :passward)");
            //変数を指定
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':day', $day, PDO::PARAM_STR);
            $sql -> bindParam(':passward', $passward, PDO::PARAM_STR);
	        $name = $_POST['name'];
            $comment = $_POST['comment'];
            $day = date("Y/m/d H:i:s");
            $passward = $_POST['passward'];
            //実行
	        $sql -> execute();

        
        //編集投稿用パスワードの確認
        }elseif(!empty($_POST['edit_number']) && $_POST['passward'] == 'edited'){
            $id = $_POST['edit_number'];
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $sql = 'UPDATE tb_mission5 SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        
        //認証失敗時
        }else{
            echo "認証失敗";
        }
    //削除
    }elseif(!empty($_POST['delete']) && !empty($_POST['delete_pass'])){
        //削除用パスワードの確認
        if($_POST['delete_pass'] == 'delete'){
            //削除したいIDの指定
            $id = $_POST['delete'];
            $sql = 'DELETE FROM tb_mission5 WHERE id=:id';  // @テーブル名内で指定したidの行の削除
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo "削除しました";
    
        //失敗時
        }else{
            echo "error";
        }
    //編集
    }elseif(!empty($_POST['edit']) && !empty($_POST['edit_pass'])){
        //編集用パスワードの確認
        if($_POST['edit_pass'] == 'edit'){
            $edit_number = $_POST['edit'];
            echo "編集モード";
        //失敗時
        }else{
            echo "error";
        }
    //ホーム表示時
    }else{
        echo "ようこそ！";    
    }

?>

<!--HTML箇所-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5</title>
</head>
<body>
    <!--送信フォーム-->
    <form action="" method="POST">
        <input type="text" name="name" value="<?php echo $format_name ?>">
        <input type="text" name="comment" value="<?php echo $format_comment ?>">
        <input type="text" name="passward" value="pass">
        <input type="submit" value="送信"><br>
        <input type="hidden" name="edit_number" value="<?php echo $edit_number ?>">
    </form>
    <!--削除フォーム-->
    <form action="" method="POST">
        <input type="number" name="delete">
        <input type="text" name="delete_pass" value="pass">
        <input type="submit" value="削除"><br>
    </form>

    <!--編集フォーム-->
    <form action="" method="POST">
        <input type="number" name="edit">
        <input type="text" name="edit_pass" value="pass">
        <input type="submit" value="編集"><br>
    </form>

    <!--ここから下に投稿内容を表示させる-->
    <hr>
    <?php
        //queryの作成
        $sql = 'SELECT * FROM tb_mission5';
        //クエリを実行
        $stmt = $pdo->query($sql);
        //取得したデータを全てフェッチする
        $results = $stmt->fetchAll();
        //データを表示する
        foreach ($results as $row){
		    echo $row['id'].' ';
		    echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['day'].'<br>';
       }
    
    
    ?>
 
</body>
</html>
