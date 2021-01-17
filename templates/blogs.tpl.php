<?php
    include 'base.tpl.php';
?>
<a href="<?=BASE?>user/loginuser" style="text-decoration:none;right:70%;color:blue;">Volver atrás</a>
<?php echo "<br>User ".$_SESSION['username'];?>

<a href="#crear" class="btn-open-popup">Crear Blog</a>
    <div class="container-all" id="crear">
        <div class="popup">
            <div class="container-text">
            <form class="form" action="<?=BASE?>blogs/blogsinsert" method="POST">
                <h2>CREATE</h2> 
                    <p type="Title:"><input type="text" placeholder="Enter title" name="title" required></p>
                    <p type="Content:"><input type="text" placeholder="Enter content" name="cont" required></p>
                    <input style="width:60%;" class="button" type="submit" value="Crear" name="crear">
                </form>
            </div>
        </div>
        <a href="#" class="btn-close-popup">X</a>
    </div>
</div>
<div>
    <a href="#update" class="btn-open-popup2">Modificar Blog</a>
    <div class="container-all" id="update">
        <div class="popup">
            <div class="container-text">
            <form class="form" action="<?=BASE?>blogs/blogsupdate" method="POST">
                <h2>UPDATE</h2> 
                    <p type="Reference Title:"><input type="text" placeholder="Enter reference title" name="title4" required></p>
                    <p type="Title:"><input type="text" placeholder="Enter new title" name="title3" required></p>
                    <p type="Content:"><input type="text" placeholder="Enter content" name="cont3" required></p>
                    <input style="width:60%;" class="button" type="submit" value="Modificar" name="update">
                </form>
            </div>
        </div>
        <a href="#" class="btn-close-popup">X</a>
    </div>
</div>
<div>
    <a href="#borrar" class="btn-open-popup3">Borrar Blog</a>
    <div class="container-all" id="borrar">
        <div class="popup">
            <div class="container-text">
            <form class="form" action="<?=BASE?>blogs/blogsdelete" method="POST">
                <h2>DELETE</h2> 
                <h5><span style="color:red;">Acuerdate!<br></span> Solo puedes borrar blogs que has creado tu!</h5>
                    <p type="Title:"><input type="text" placeholder="Enter title" name="title2" required></p>
                    <input style="width:60%;" class="button" type="submit" value="Borrar" name="borrar">
                </form>
            </div>
        </div>
        <a href="#" class="btn-close-popup">X</a>
    </div>
</div>

<?php

include 'footer.tpl.php';

?>