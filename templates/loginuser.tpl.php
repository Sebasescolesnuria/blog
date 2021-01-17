<?php

    include 'base.tpl.php';
    
    $logout = filter_input(INPUT_POST,"logout");

    if ($logout == TRUE){
        session_destroy();
        setcookie("tiempovisita","");
        setcookie("username","");
        setcookie("email","");
        header('Location:'.BASE.'user/login');
    }
?>
    <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;">
        <p>Te has logeado correctamente, bienvenido <?php echo $_SESSION["username"];?>!</p>
        <p><a style="text-decoration:none;color:blue;" href="<?=BASE?>blogs/blogs">Ver blogs</a></p>
        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
            <br><input type="submit" name="logout" class="btn btn-info" value="Logout">
        </form>
    </div>
<?php

include 'footer.tpl.php';

?>