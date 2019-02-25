<?php
session_start();
if ($_SESSION["Nome_tarefa"]){
    unset($_SESSION["Nome_tarefa"]);
    header('Location: ../login.php');    
}

?>

