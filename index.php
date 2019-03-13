<?php
include_once("services/tarefas.php");
include_once("services/sql_conexao.php");
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


?>


<!DOCTYPE html>
<html lang="pt-br">

<!--IMPORTACAO DO HEADER-->    
<?php
include_once("header.php");
?>

<body class="tes">



<!--IMPORTACAO DO MENU--> 
<?php
include_once("menu.php");

?>

<!--INICIO DO CONTAINER-->
<div class="container-fluid theme-showcase" role="main">

    <!--TITULO LISTAR RHS-->
    <div class="page-header">
        <h1 style="text-align: center;">Tarefas</h1>
    </div>




</div>

    
    <div class="row" style="display: inherit; margin-top: 40px">
        <div class="col-12">
        
            <form method="POST">
            
                <div class="form-row">                    
                    <div class="row col-12" style="margin:0 auto">
                    
                        <select name='setor' id='setor' class='form-control form-control-lg col-4' style="background: aqua;display: inherit;margin-right: 40px;">
                            <option disabled selected> Selecione um Setor</option>
                            <?php
                            while($array = sqlsrv_fetch_array($query_fabrica)){?>
                                <option id="option_setor" value="<?php echo $array['ID_FABRICA']; ?> "><?php echo $array['FABRICA_DESCR']; ?>  </option> <?php

                                }
                                
                            ?>
                        </select>                        
                        <select name='recurso' id='recurso' class='form-control form-control-lg col-4' style="background: aqua; display:none;">
                            <option  disabled selected>Recurso</option>
                            <?php
                            
                            
                            ?>
                        </select>                       
                    </div>                    
                </div>
            </form>

        </div>
    </div>   

    
    <!--TABELA LISTAR TAREFAS-->
    <div class="row" id="tabela_listar_rhs" STYLE="display: inherit;">
        <div class="col-md-12 table-striped table-responsive shadow p-3 mb-5 bg-white rounded">
            <table class="table table-hover">
                <thead class="">
                <tr>
                    <th style='font-weight: bold;'>PRODUTO</th>
                    <th style='font-weight: bold;'>QTDE</th>
                    <th style='font-weight: bold;'>CLIENTE </th>
                    <th style='font-weight: bold;'>DATA.OP</th>
                    <TH style='font-weight: bold;'>AÇÃO</TH>
                    <th style='font-weight: bold;'>PCP</th>
                    
                    <?php
                        $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
                        if($SendPesqUser){
                            echo "<th>"; 
                            echo "<a href=index.php style='color: inherit'</a><button type=button class='btn btn-xs btn-dark'>Voltar</button>";
                            echo "</th>";
                            
                        }
                        
                    ?>
                    </th>
                </tr>
                </thead>

                <tbody id="corpo_tabela">

                <!--Inicio Loop com pesquisar-->
                <?php
                $pesquisar = filter_input(INPUT_POST, 'pesquisar', FILTER_SANITIZE_STRING);
                if($pesquisar){     
                    $recurso = filter_input(INPUT_POST, 'recurso', FILTER_SANITIZE_STRING);                   
                    
                    $tarefas->pesquisar($recurso);
                        

                    } 
                    
                

                ?>	                
                <!-- Inicio Loop sem pesquisar-->                 
                <tr >
                    <?php
                        if(!$SendPesqUser){                                   
                            $tarefas->pesquisar(""); 
                            
                        }
                    ?>
                </tr>         
                <?php

                    $tarefas->vizualizarModal();

                ?>
                </tbody>
            </table>            
        </div>
    </div>   
</div>


<!-- MODAIS -->
<?php
$tarefas->legenda();

?>
<!----------->



<!--IMPORTACAO FOOTER-->
<?php
include_once("footer.php");
?>

<script src="public/js/modal_apagar.js"></script>

<script src="public/js/bootstrap-4.1.js"></script>


<!--SCRIPT AJAX PROCURAR-->
<script>
    $('#setor').on('change',function(){
        $("#recurso").show("fast");
        var setor = $("#setor option:selected").val();
        console.log(setor);
            $.ajax({
                type: "POST",
                url: "services/ajax_setor.php",
                data: {setor : setor},
                beforeSend : function () {
                        console.log('Carregando...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    //alert(retorno);
                    $("#recurso").html(retorno);
                    
                },
                                        
                error:function(data){
                }
            });
            
        });
        
            $('#recurso').on('change',function(){
            var recurso = $("#recurso option:selected").text();
            console.log(recurso);
            $.ajax({
                type: "POST",
                url: "services/tarefas.php",
                data: {recurso : recurso},
                beforeSend : function () {
                        console.log('Carregando...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    $( "recurso" ).show( "slow" );
                    //alert(retorno);
                    
                    $("#corpo_tabela").html(retorno);
                },
                                        
                error:function(data){
                }
            });
        });       
        

        
</script>
</body>
</html>