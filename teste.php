<?php

include_once("services/sql_conexao.php");

include_once("services/funcoes.php");

$funcoes = new funcoes();

$tempo = $funcoes->HrDecToString(-0.1067);

echo $tempo;


?>