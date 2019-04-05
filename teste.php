<?php

include_once("services/sql_conexao.php");

include_once("services/funcoes.php");

$funcoes = new funcoes();

$conexao = new conexao($database);

$conexao->sql_conexao("TAREFAS");

$sql = " EXEC FS_NEW.DBO.SP_GetApontamento 'tor1'";
	

$query = sqlsrv_query($conexao->con, $sql);


while($array = sqlsrv_fetch_array($query)){
    $tempo = $array['HR_SALDO'];
    echo $funcoes->HrDecToString($tempo ) ."-";
    echo $tempo;
}


?>