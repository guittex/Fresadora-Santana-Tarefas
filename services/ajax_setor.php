<?php
include_once("sql_conexao.php");
$conexao = new conexao($database);

$conexao->sql_conexao("TAREFAS");

if($_POST){
    $setor=$_POST['setor'];
}

if($_GET){
    $setor=$_GET['setor'];

}


//print_r("cheguei ate o post");


//echo $exibe;
$sql_recurso= "
SELECT 
f.codFabrica  AS ID_FABRICA    ,
f.descricao   AS FABRICA_DESCR ,
R.CODMAQUINA  AS ID_RECURSO    ,
R.NOME        AS RECURSO_NOME  ,
R.Finito     

FROM
VENDAS.dbo.Fabrica F 
INNER JOIN 
COLETA.dbo.TAB_Recursos R ON 
f.codFabrica  = R.Fabrica 
AND R.ATIVO   = 'S'
--AND Finito    = 'S'
WHERE 
f.codFabrica  = '$setor'

ORDER BY 
3   ";
	
//echo $setor;
$query_recurso = sqlsrv_query($conexao->con, $sql_recurso);

if($_POST){
    echo "<option disabled selected>Recurso</option>";
    while($array2 = sqlsrv_fetch_array($query_recurso)){?>
                                    

    <option id="recurso_option" value="<?php echo $array2['ID_RECURSO']; ?> "><?php echo $array2['ID_RECURSO']; ?>  </option> <?php
    } 
}
