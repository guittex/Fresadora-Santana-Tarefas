<?php   

include_once("sql_conexao2.php"); 

$conexao = new conexao();

$conexao->sql_conexao();

$user = $_POST['usuarioEmail'];
$senha = $_POST['Senha'];

//echo $user;
//echo $senha;

$sql = " SELECT * FROM dbo.TB_Usuario WHERE usuarioEmail = '$user' and Senha = '$senha' " ;

$query = sqlsrv_query($conexao->con, $sql);

$registro = sqlsrv_fetch_array($query);

if($registro == NULL){
    die('Falha ao autenticar usuário');
}
session_start();
$_SESSION["login"] = $user;
header('Location:../index.php');





?>

