<?php
include_once("sql_conexao.php");

include_once("tarefas.php");

$tarefas = new tarefas();

$conexao = new conexao($database);

$conexao->sql_conexao("TAREFAS");


$sql_fabrica = "
SELECT 
    f.codFabrica  AS ID_FABRICA    ,
    f.descricao   AS FABRICA_DESCR ,
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
    2 ";     

$query_fabrica = sqlsrv_query($conexao->con, $sql_fabrica);

echo $query_fabrica;
    ?>
    <select name='setor' id='select_setor' class='form-control form-control-lg col-3' style='background:white;color:black!important'>

    <option disabled selected>  Setor</option><?php

while($array = sqlsrv_fetch_array($query_fabrica)){?>

    <option id="option_setor" value="<?php echo $array['ID_FABRICA']; ?> "><?php echo $array['FABRICA_DESCR']; ?>  </option> 
    </select>  
    <?php

    }



/*
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
*/

?>