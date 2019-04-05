<?php
include_once("sql_conexao.php");

include_once("tarefas.php");

$tarefas = new tarefas();

$conexao = new conexao($database);

$conexao->sql_conexao("TAREFAS");

$sql = "SELECT 
        top 1
        f.codFabrica  AS ID_FABRICA    ,
        f.descricao   AS FABRICA_DESCR ,
        NEWID() AS aleatorio,
        COUNT(1)      AS RECURSOS

    FROM
        VENDAS.dbo.Fabrica F 
        INNER JOIN 
        COLETA.dbo.TAB_Recursos R ON 
        f.codFabrica  = R.Fabrica 
        AND R.ATIVO   = 'S'
        AND F.FINITO = 'S'
        AND R.Finito  = 'S'

    GROUP BY 
        f.codFabrica  ,
        f.descricao   

    ORDER BY 
        aleatorio";

$query = sqlsrv_query($conexao->con,$sql);

$array = sqlsrv_fetch_array($query); 

$setor = $array['ID_FABRICA'];
$titulo = $array['FABRICA_DESCR'];

$tarefas->painel_listar($setor,$titulo);
?>

<script>
var seg = 20;

relogio();

</script>

