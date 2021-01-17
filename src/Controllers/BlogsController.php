<?php

    namespace App\Controllers;
    use App\Controller;
    use App\View;
    use App\Model;
    use App\Request;
    use App\Session;
    //include 'TasksController.php';
    //require 'TasksController.php';

    class BlogsController extends Controller implements View,Model{
        public function __construct(Request $request, Session $session){
            parent::__construct($request,$session);
        }

        function blogs(){
            $db=$this->getDB();
            $dataview = ['title'=>'blogs'];
            $this->render($dataview,"blogs");
            self::blogsselect();
        }

        function selectorName($name){
            $db=$this->getDB();
            $stmt=$db->prepare('SELECT * FROM user WHERE id=:uname LIMIT 1');
            $stmt->execute([':uname'=>$name]);
            $row=$stmt->fetchAll();  
            $array = $row[0]['username'];
            return $array;
        }

        function selectorID($id){
            $db=$this->getDB();
            $stmt=$db->prepare('SELECT * FROM user WHERE username=:uname LIMIT 1');
            $stmt->execute([':uname'=>$id]);
            $row=$stmt->fetchAll();  
            $array = $row[0];
            $id = $array['id'];
            return $id;
        }

        function blogsselect(){
            $db=$this->getDB();
            $user = $_SESSION["username"];
            $idblog = [];
            $titleblog = [];
            $contblog = [];
            $usernameblog = [];
            $createdateblog = [];
            $modifydateblog = [];
            $count = 0;
            $blogs = false;
            
            try{  
                $command = "SELECT * FROM post ORDER BY user DESC";
                $stmt=$db->prepare($command);
                $stmt->execute();
                $count=$stmt->rowCount();
                $row=$stmt->fetchAll();
                $count = count($row);
                $array = array();

                if ($count != 0){
                    echo "<div style='display:flex;justify-content:center;align-items:center;flex-wrap:wrap;'>";
                    foreach($row as $rows){
                        echo "
                            <div style='margin-top:5%;width:100%;background-color:white;border-radius:8px;border:1px solid black;display:flex;
                            flex-direction:row;justify-content:center;align-items:center;text-align:center;'>
                            <p style='width:25%;'>Title: ".$rows['title']."</p>
                            <p style='width:25%;'>Creator: ".self::selectorName($rows['user'])."</p>
                            <p style='width:25%;'>Created: ".$rows['create_date']."</p>
                            <p style='width:25%;'>Last modify: ".$rows['modify_date']."</p></div>
                            <div style='height:50px;width:100%;background-color:white;border-radius:8px;border:1px solid black;display:flex;
                            flex-direction:row;justify-content:center;align-items:center'>Content: ".$rows['cont']."</div>
                        ";
                        self::commentsselect($rows['id']);  
                    }  
                    echo "</div>";
                } 
            }catch(PDOException $e){
                die($e -> getMessage());
            }
        }

        function blogsinsert(){
            $db=$this->getDB();
            $name = $_SESSION["username"];
            $title = filter_input(INPUT_POST,"title");
            $cont = filter_input(INPUT_POST,"cont");
            $createdate = date("Y-m-d");
            $modifydate = $createdate;
            
            try{
                $id = self::selectorID($name);
                $stmt3=$db->prepare("
                INSERT INTO post (title,cont,user,create_date,modify_date) VALUES (:title,:cont,:id,:create_date,:modify_date)");

                if ($stmt3->execute(['title'=>$title,'cont'=>$cont,'id'=>$id,'create_date'=>$createdate,'modify_date'=>$modifydate])){
                    header('Location:'.BASE.'blogs/blogs');
                }
            }catch(PDOException $e){
                die($e -> getMessage());
            }
        }

        function blogsupdate(){
            $db=$this->getDB();
            $name = $_SESSION["username"];
            $title = filter_input(INPUT_POST,"title3");
            $oldtitle = filter_input(INPUT_POST,"title4");
            $cont = filter_input(INPUT_POST,"cont3");
            $createdate = date("Y-m-d");
            $modifydate = $createdate;
            
            try{
                $id = self::selectorID($name);
                $stmt3=$db->prepare("
                UPDATE post SET title=:title,cont=:cont,modify_date=:modify_date WHERE title=:oldtitle AND user=:user");

                if ($stmt3->execute(['title'=>$title,'cont'=>$cont,'modify_date'=>$modifydate,"oldtitle"=>$oldtitle,"user"=>$id])){
                    header('Location:'.BASE.'blogs/blogs');
                }
            }catch(PDOException $e){
                die($e -> getMessage());
            }
        }

        function blogsdelete(){
            $db=$this->getDB();
            $name = $_SESSION["username"];
            $title = filter_input(INPUT_POST,"title2");
            $borrar = filter_input(INPUT_POST,"borrar");

            $id = self::selectorID($name);
            $stmt2=$db->prepare("
            DELETE FROM post WHERE user = $id AND title = :title");

            if ($stmt2->execute([':title'=>$title])){
                header('Location:'.BASE.'blogs/blogs');
            }
        }

        function commentsselect($idpost){
            $db=$this->getDB();
            $id = [];
            $comment = [];
            $user = []; 
            $post = [];
            $count = 0;
            $i = 0;

            $command = "SELECT * FROM comments WHERE post = $idpost";
            $stmt=$db->prepare($command);
            $stmt->execute();
            $count=$stmt->rowCount();
            $row=$stmt->fetchAll();
            $arraycomments = array();

            if ($count != 0){
                echo "<div style='min-height:50px;width:50%;background-color:white;border-radius:8px;border:1px solid black;display:flex;
                flex-direction:row;justify-content:center;align-items:center;text-align:center;'
                <p style='margin-left:5px;'>Comments</p></div>";
                foreach($row as $paco3){
                    $arraycomid = $paco3['id'];
                    $arraycomcom = $paco3['comment'];
                    $arraycomuser = self::selectorName($paco3['user']);
                    $arraycompost = $paco3['post'];
                    echo "<div style='min-height:50px;width:50%;background-color:white;border-radius:8px;border:1px solid black;display:flex;
                    flex-direction:row;justify-content:center;align-items:center;text-align:center;'<p>$arraycomuser: $arraycomcom</p></div>";
                }   
            }
            echo '<form style="width:50%;display:flex;flex-direction:row;justify-content:center;align-items:center;
            background-color:white;border:1px solid black;padding-left:10px;padding-right:10px;" action="'.BASE.'blogs/commentinsert" method="POST">
            <p type="Añadir comentario:"><input type="text" placeholder="Enter comment" name="content" required></p>
            <button style="max-height:40px;" type="submit" style="width:60%;" class="button" type="submit" value="'.$idpost.'" name="crearcomment">Enviar</button></form>';  
        }

        function commentinsert(){
            $db=$this->getDB();
            $idpost = filter_input(INPUT_POST,"crearcomment");
            $content = filter_input(INPUT_POST,"content");
            $user = self::selectorID($_SESSION["username"]);

            $command = "INSERT INTO comments (comment,user,post) VALUES (:comment,:user,:post)";
            $stmt=$db->prepare($command);
            if($stmt->execute(["comment"=>$content,"user"=>$user,"post"=>$idpost])){
                header('Location:'.BASE.'blogs/blogs');
            }

        }
    }

?>