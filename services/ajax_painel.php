<?php

$setor = trim($_POST['setor']);
$nome_setor = trim($_POST['nome_setor']);

//echo $nome_setor;

echo "<div class=divSetor_select>";
echo "<a href='http://192.168.1.214:8086/lista_tarefas/lista_tarefas/painel_index.php?setor=$setor&titulo=$nome_setor'> <button name='painel' id='btn_geral' type=button class='btn btn-success' style=margin-left:50px>Painel</button></a>";
echo "</div>";


?>