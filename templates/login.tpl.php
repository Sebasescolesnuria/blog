<?php

    include 'base.tpl.php';
    
?>

<form class="form" action="<?=BASE?>user/loginform" method="POST">
 <h2>LOGIN <a style="text-decoration:none;color:#78788c;font-size:12px;" href="<?=BASE?>user/register">REGISTER</a> </h2> 
    <p type="Email:"><input type="text" placeholder="Enter your email" name="email" required></p>
    <p type="Name:"><input type="text" placeholder="Enter your name" name="uname" required></p>
    <p type="Password:"><input type="password" placeholder="Enter your password" name="passw" required></p>  
    <input style="width:60%;" class="button" type="submit" value="Login">
</form>

