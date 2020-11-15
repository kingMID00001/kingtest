<?PHP
//■■SQLに接続■■
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//PHP Data Objectsの略、PHPからデータベースのアクセスを抽象的にしてくれるもの
//$pdoにPDOインスタンスが生成される。//arrayはオプション
//■■接続完了■■

//■■テーブルを作る CREATE TABLE文■■
//投稿番号　名前　コメント　日時　パスワード
//id　　　　name　comment　 date　pass
$sql="CREATE TABLE missiontb"
."("
."id INT not null auto_increment primary key,"
//投稿番号 id(カラム名) int(整数) NOT NULL制約(空白を入れない)
//AUTO_TNCREMENT (自動採番) PRIMARY KEY 
."name char(32),"//名前 CHAR型 32文まで
."comment TEXT,"//コメント TEXT型
."date DATETIME,"//投稿日時 日時型
."pass TEXT" //パスワード TEXT型 
.");";
$stmt=$pdo->
// query($sql);//接続実行 
prepare($sql);
//■■テーブル作成完了■■
// ("(name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");//テーブルのN,C,D,Pに変数の形を変えた:nameなどをVALUESに入れた。
// $sql -> bindParam(':name', $name, PDO::PARAM_STR);
// $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
// $sql -> bindParam(':date', $date, PDO::PARAM_STR);
// $sql -> bindParam(':pass', $newPass, PDO::PARAM_STR);
// $sql -> execute();//executeでクエリを実行！
?>


<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>

<?php
//■■新規投稿■■
if(isset($_POST['sub']))
{
if(!empty($_POST['name'] && $_POST['comment'] && $_POST['newPass']) && empty($_POST['dpEdit']))
{
//SQLに接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//定義
  $name=$_POST["name"];
  $comment=$_POST["comment"];
  $date = date("Y/m/d/ H:i:s");
  $pass=$_POST["newPass"];
//データを入力
  $sql=$pdo -> prepare("INSERT INTO missiontb (name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");//テーブルのN,C,D,Pに変数の形を変えた:nameなどをVALUESに入れた。
  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
  $sql -> bindParam(':date', $date, PDO::PARAM_STR);
  $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
  $sql -> execute();//executeでクエリを実行！
//第１引数はテーブルのパラン、第２引数phpの変数
}
elseif(!empty($_POST['name'] && $_POST['comment'] && $_POST['newPass'] && $_POST['dpEdit']))
{
  //SQLに接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

  //missiontbの登録されたデータを取得する
  $sql = 'SELECT * FROM missiontb';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
    foreach ($results as $row)
    {
      if($row['id'] == $_POST['dpEdit'])//投稿番号と表示されてる番号が一致してたら書き換える。
       {
        $id = $_POST['dpEdit'];//変更する投稿番号
        //変更したい内容を入力
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date = date("Y/m/d/ H:i:s");
        $pass=$_POST["newPass"];

        $sql = 'UPDATE missiontb SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);// PDOStatementクラスのインスタンスを生成します。
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
       }

    }
}
}
?>






<?php
//■■削除PHP■■
if(isset($_POST["deleteSub"])){
  if(!empty($_POST["deleteNo"] && $_POST["deletePass"])){
    //変数定義
    $deletePass=$_POST["deletePass"];
    $delete=$_POST["deleteNo"];

    //SQLに接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //missiontbの登録されたデータを取得する
    $sql = 'SELECT * FROM missiontb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row)
    {
      if( $row['id'] == $delete && $row['pass'] == $deletePass)
      {//投稿番号＝削除番号 & パスワードと記入パスワードが一致した場合
        //delete文で対象レコード(id name comment date pass)を削除
        $id = $delete;//id番号は削除番号
        $sql = 'delete from missiontb where id=:id';//ID=d$eleteを消す
      	$stmt = $pdo->prepare($sql);
	      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
      }
    }
  }
}
?>  


<?php
  //■■編集表示■■
  if(isset($_POST["editSub"]))
  {
    if(!empty($_POST["editNo"]))
    {
      //変数定義
      $editNo=$_POST["editNo"];
      $editPass=$_POST["editPass"];
      //SQLに接続
      $dsn = 'データベース名';
      $user = 'ユーザー名';
      $password = 'パスワード';
      $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      //missiontbの登録されたデータを取得する
      $sql = 'SELECT * FROM missiontb';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row)
      {
        if($row['id'] == $editNo && $row["pass"] == $editPass)
        {
          $ENO=$row['id'];
          $ENA=$row['name'];
          $ECO=$row['comment'];
        } 
      }   
    }
  }  
  ?>
      <form action="" method="post">
            
        <div>投稿</div>
        <input type="text" name="name" placeholder="名前" 
        value="<?php  if(isset($_POST["editSub"])){echo $ENA;}?>"><br>
        <input type="text" name="comment" placeholder="コメント" 
        value="<?php  if(isset($_POST["editSub"])){echo $ECO;}?>"><br>
        <input type="password" name="newPass" placeholder="パスワード"><br>
        <input type="submit" value="送信" name="sub"><br>
        <input type="hidden" name="dpEdit"
        value="<?php  if(isset($_POST["editSub"])){echo $ENO;}?>"><br>>
        <!--style="display:none;"-->
   
        <div>削除</div>
        <input type="number" name="deleteNo" placeholder="削除対象番号"><br>
        <input type="password" name="deletePass" placeholder="パスワード"><br>
        <input type="submit" value="削除" name="deleteSub"><br>
       
        <div>編集</div>
        <input type="text" name="editNo" placeholder="編集対象番号"><br>
        <input type="password" name="editPass" placeholder="パスワード"><br>
        <input type="submit" value="編集" name="editSub"><br>
        
       </form>
    
<?php
?>

<?php
//表示エラー
  if(isset($_POST["sub"]) && empty($_POST["name"])){
    echo "■■error 名前が入力されていません■■"."<br>";
  }
  if(isset($_POST["sub"]) && empty($_POST["comment"])){
      echo "■■error コメントが入力されていません■■"."<br>";
  }
  if(isset($_POST["deleteSub"]) && empty($_POST["deleteNo"])){
      echo "■■error 削除番号が入力されていません■■"."<br>";
  }
  if(isset($_POST["editSub"]) && empty($_POST["editNo"])){
      echo "■■error 編集番号が入力されていません■■"."<br>";
  }

  if(isset($_POST["deleteSub"]))
  {
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //missiontbの登録されたデータを取得する
    $sql = 'SELECT * FROM missiontb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    }
    if($row['pass'] !== $_POST['deletePass'])
     {
      echo "■■パスワードが間違ってます■■"."<br>";
     }
    }
  
    if(isset($_POST["editSub"]))
    {
      $dsn = 'データベース名';
      $user = 'ユーザー名';
      $password = 'パスワード';
      $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      //missiontbの登録されたデータを取得する
      $sql = 'SELECT * FROM missiontb';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
      }
      if($row['pass'] !== $_POST['editPass'] )
      {
        echo "■■パスワードが間違ってます■■"."<br>";
      }
    }
    ?>


<?php
  if(isset($_POST["sub"]) || isset($_POST["deleteSub"]) || isset($_POST["editSub"])){
    //SQLに接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //データレコードを抽出し、表示する。
    $sql ='SELECT * FROM missiontb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){

      //$rowの中にはテーブルのカラム名が入る
      echo $row['id']." ".$row['name']." ".$row['comment']." ".$row['date'].'<br>';
    }
  } 
  ?>

  </body>
  </html>