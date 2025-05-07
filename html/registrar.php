<?php 

include("con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['name']) >= 1 && strlen($_POST['email']) >= 1) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $fechareg = date("d/m/y");
        $consulta = "INSERT INTO datos(nombre, email, fecha_reg) VALUES ('$name','$email','$fechareg')";
        $resultado = mysqli_query($conex, $consulta);
        
        if ($resultado) {
            ?> 
            <div class="message success">¡Te has inscripto correctamente!</div>
            <?php
        } else {
            ?> 
            <div class="message error">¡Ups ha ocurrido un error!</div>
            <?php 
        }
    } else {
        ?> 
        <div class="message error">¡Por favor complete los campos!</div>
        <?php
    }
}

?>