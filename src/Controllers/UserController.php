<?php

    namespace App\Controllers;
    use App\Controller;
    use App\View;
    use App\Model;
    use App\Request;
    use App\Session;
    //use App\DB;

    class UserController extends Controller implements View,Model{
        public function __construct(Request $request, Session $session){
            parent::__construct($request,$session);
        }

        function dashboard(){
            
            $user=$this->session->get('user');
            $data=$this->getDB()->selectAllWithJoin('tasks','users',['tasks.id','tasks.description','tasks.due_date'],'user','id');
            $this->render(['user'=>$user,'data'=>$data],'dashboard');
        }
        
        public function register(){
            $db=$this->getDB();
            $dataview = ['title'=>'register'];
            $this->render($dataview,"register");
        }
        
        public function registerform(){
            $db=$this->getDB();
            $user = filter_input(INPUT_POST,"uname");
            $password = filter_input(INPUT_POST,"passw");
            $email = filter_input(INPUT_POST,"email");
            $role = 1; // El rol 1 significa que es tipo usuario, todos los nuevos usuarios comienzan con este rol
            $tiempo = date("d-M-Y H:i:s");

            $cost = ['cost' => 4];
            $password = password_hash($password, PASSWORD_BCRYPT, $cost);

            try{ //Repito el código de SELECT dado que si no lo utilizo en esta función el register no me funciona correctamente, si el usuario existe no me da error SQL y me dice que se ha insertado pero no se inserta, y si no existe si que lo inserta
                $stmt=$db->prepare('SELECT * FROM user WHERE email=:email LIMIT 1');
                $stmt->execute([':email'=>$email]);
                $count=$stmt->rowCount();
                $row=$stmt->fetchAll();  
                
                if($count == 1){       
                    $register = false;
                }
                else{
                    $command2 = "
                    INSERT INTO user (username,email,passwd,rol) VALUES ('$user','$email','$password',$role)";
                    $db -> exec($command2);
                    $register =  true;
                }
            }catch(PDOException $e){
                $register = false;
                die($e -> getMessage());
            }

            if ($register){ //Lee si la función ha devuelto true
                $_SESSION["username"] = $user; 
                $_SESSION["email"] = $email; 
                setcookie("email",$email,"/");  
                setcookie("username",$user,"/"); 
                setcookie("tiempovisita",$tiempo,"/");
                $dataview = ['title'=>'loginuser'];
                $this->render($dataview,"loginuser"); //Redirige a la página principal con cookies
                //echo "Registre 1";
            }
            else{ //Si la función devuelve false significa que el usuario ya existe en la base de datos
                header('Location:'.BASE.'user/register'); //Redirigimos al usuario a register
                //echo "Registre 2";
            }
        }

        public function login(){
            $db=$this->getDB();
            $dataview = ['title'=>'login'];
            $this->render($dataview,"login");
            //$this->render(null,"login");
        }

        public function loginuser(){
            $db=$this->getDB();
            $dataview = ['title'=>'login'];
            $this->render($dataview,"loginuser");
            //$this->render(null,"login");
        }

        public function loginform(){
            $db=$this->getDB();
            $email = filter_input(INPUT_POST,"email");
            $password = filter_input(INPUT_POST,"passw");
            $user = filter_input(INPUT_POST,"uname");
            $recordar = filter_input(INPUT_POST,"recordar");
            $tiempo = date("d-M-Y H:i:s");

            try{   
                $stmt=$db->prepare('SELECT * FROM user WHERE email=:email LIMIT 1');
                $stmt->execute([':email'=>$email]);
                $count=$stmt->rowCount();
                $row=$stmt->fetchAll();  
                
                if($count == 1){       
                    $array = $row[0];
                    $res = password_verify($password,$array['passwd']);
                   
                    if ($res){
                        $_SESSION['username'] = $array['username'];
                        $_SESSION['email'] = $array['email'];
                        $login = true;
                    }else{
                        $login = false;
                    }
                }else{
                    $login = false;
                }
            }catch(PDOException $e){
                $login = false;
                die($e -> getMessage());
            }
            
            if ($login){ //Lee si la función ha devuelto true 
                setcookie("email",$email,"/");  //Creamos cookie para guardar el nombre
                setcookie("username",$user,"/");  
                setcookie("tiempovisita",$tiempo,"/"); 
                $dataview = ['title'=>'loginuser'];
                $this->render($dataview,"loginuser");
                //echo "Login 1";
            }
            else{ //Lee si la función ha devuelto false para redirigir al login
                $dataview = ['title'=>'login'];
                $this->render($dataview,"login");
                //echo "Login 3";
            }
        }
    }

?>