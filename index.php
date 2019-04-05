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

$recurso = "TOR1";

if(!empty($_GET['recurso'])){
    $recurso = $_GET['recurso'];
    //echo $recurso;
}


if(!empty($recurso)){

echo "<script>var recurso = '". $recurso ."';
            console.log(recurso);        
        </script>";
//echo "to no recurso volta ajax nao";

}


if(!empty($_GET['atividade'])){
    $atividades = $_GET['atividade'];
}


?>
<style>



</style>

<!DOCTYPE html>
<html lang="pt-br">

<?php
$tarefas->getApontamento();

$atividades = $tarefas->produto_apontado['ATIVIDADE'];


?>

<!--IMPORTACAO DO HEADER-->    
<?php
include_once("header.php");
?>



<body class="tes" style='background: white!important;'>

<!--IMPORTACAO DO MENU--> 
<?php
include_once("menu.php");


?>

<!--INICIO DO CONTAINER-->
<div class="container-fluid theme-showcase" role="main">

    <!--TITULO LISTAR RHS-->
    <div class="page-header text-center">
        <!--<img id='btn_prev' src=public/img/voltar.png>-->
        <input id='recurso_titulo' class='text-center' value='<?php if(!empty($_GET["recurso"])){ echo $recurso; }else{ echo 'TOR1'; }?>' style='width: 193px;margin-left:5px;border: none;padding-left: -1;font-size:37px !important' readonly>
        <!--<img id='btn_proximo' src=public/img/proximo.png style='max-width: 75%!important;'>-->

    </div>

</div>


    <div class="row" STYLE="display: inherit;">
        <div class="col-md-12 table-striped table-responsive shadow p-3 mb-5 bg-white rounded" style=padding:0px!important>
            <div class="row" id="linha_atividade" style='background:<?php echo $tarefas->background_atv ?>'>
                <div class='col-12' style='margin-top:30px'>
                    <div class='form-group row'>
                        <p class='label_tabela' id='label_tabela' style=font-weight:bold;margin-top:10px>ATIVIDADE:</p>
                        <div class='col-sm-10'>
                            <input  class=form-control  id='atividade' style='font-size:24px!important;border:none;background:none' value='<?php echo $atividades ?>' readonly>
                        </div>
                    </div>                            
                </div>
            </div>
        </div>
    </div>



    <div class="row" style="display: inherit; margin-top: 40px">
        <div class="col-12">
        
            <form method="POST">
            
                <div class="form-row">                    
                    <div class="row col-12" style="margin-bottom:20px">
                    
                        <select name='setor' id='setor' class='form-control form-control-lg col-3' style="background: aqua;display: inherit;margin-right: 40px;">
                            <option disabled selected>  Setor</option>
                            <?php
                            while($array = sqlsrv_fetch_array($query_fabrica)){?>
                                <option id="option_setor" value="<?php echo $array['ID_FABRICA']; ?> "><?php echo $array['FABRICA_DESCR']; ?>  </option> <?php

                                }
                                
                            ?>
                        </select>                        
                        <select name='recurso' id='recurso' class='form-control form-control-lg col-3' style="background: aqua; display:none;margin-right: 30px;">
                            <option  disabled selected>Recurso</option>
                            <?php
                            
                            
                            ?>
                        </select> 
                        <button name='pesquisa' id='btn_geral' type="button" class="btn btn-primary w-25" data-toggle='modal' data-target='#pesquisar_modal'>Produto</button>
						
                        <div class="divSetor_select">
                            <button name='painel' id='btn_geral' type="button" data-toggle='modal' data-target='#selecionar_setor' class="btn btn-danger" style=margin-left:50px>Painel</button>
                        </div>

                    </div>                    
                </div>
            </form>

        </div>
    </div>   

    
    <!--TABELA LISTAR TAREFAS-->
    <div class="row" id="tabela_listar_rhs" STYLE="display: inherit;">
        <div class="col-md-12 table-striped table-responsive shadow p-3 mb-5 bg-white rounded" style=padding:0px!important>
            <table class="table table-hover">
                <!--<thead class="">
                <tr>
                    <th style='font-weight: bold;'>PRODUTO</th>
                    <th style='font-weight: bold;'>QTDE</th>
                    <th style='font-weight: bold;'>CLIENTE </th>
                    <th style='font-weight: bold;'>DATA.OP</th>
                    
                    
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
                </thead> -->

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
                            $tarefas->pesquisar($recurso); 
                            
                        }
                    ?>
                </tr>         
                
                </tbody>
            </table>            
        </div>
    </div>   
</div>


<!-- MODAIS -->
<?php
$tarefas->legenda();
$tarefas->pesquisar_op();
$tarefas->razaoDo_nome();
$tarefas->selecionar_setor();

?>
<!----------->



<!--IMPORTACAO FOOTER-->
<?php
include_once("footer.php");
?>

<script src="public/js/modal_apagar.js"></script>

<script src="public/js/bootstrap-4.1.js"></script>

<script>

//document.getElementById('recurso_input').value = "<?php echo $recurso ?>";             

</script>



<!--SCRIPT AJAX PROCURAR-->
<script>
    $('#setor').on('change',function(){
        
        $("#recurso").show("fast");
        var setor = $("#setor option:selected").val();     
        var nome_setor = $("#setor option:selected").text();     

        console.log(nome_setor);
        console.log(setor);
            //AJAX PARA SELEÇÃO DO SETOR
            $.ajax({
                type: "POST",
                url: "services/ajax_setor.php",
                data: {setor : setor},
                beforeSend : function () {
                        console.log('Carregando Setor Select...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    //alert(retorno);                                            
                    $("#recurso").html(retorno);
                    
                },
                                        
                error:function(data){
                }
            });

            //AJAX PARA ABRIR PAINEL
            $.ajax({
                type: "POST",
                url: "services/ajax_painel.php",
                data: {setor : setor , nome_setor : nome_setor},
                beforeSend : function () {
                        console.log('Carregando Setor Painel...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    //alert(retorno);                                     
                    $(".divSetor_select").html(retorno);
                    
                },
                                        
                error:function(data){
                }
            });
            
        });   



            <?php

            if(empty($_GET['recurso'])){
                echo "$('#recurso').on('change',function(){";
                    
                    
                echo "var recurso = $('#recurso option:selected').text();";
            ?>
            
            console.log(recurso);
            
            $.ajax({
                type: "POST",
                url: "services/tarefas.php",
                data: {recurso : recurso},
                beforeSend : function () {
                        console.log('Carregando AJAX IF...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    $( "recurso" ).show( "slow" );
                    document.getElementById('recurso_titulo').value = recurso;
                    
                    //alert(retorno);
                    
                    $("#corpo_tabela").html(retorno);
                },
                                        
                error:function(data){
                }
            });
        <?php
            echo "})";  
            
            }else{
                
                ?>
            $('#recurso').on('change',function(){
                var recurso = $('#recurso option:selected').text()
                console.log(recurso);
                
                $.ajax({
                    type: "POST",
                    url: "services/tarefas.php",
                    data: {recurso : recurso},
                    beforeSend : function () {
                            console.log('Carregando AJAX else...');
                    },
                    success : function(retorno){
                        //traz o retorno do que pegou do php 
                        $( "recurso" ).show( "slow" );
                        document.getElementById('recurso_titulo').value = recurso;                        

                        //alert(retorno);
                        
                        $("#corpo_tabela").html(retorno);
                    },
                                            
                    error:function(data){
                    }
                });
            });

        <?php }?>

        $( "#btn_proximo" ).click(function() {
            var recurso = $('#recurso option:selected').text()

            console.log(recurso);
            
            $.ajax({
                type: "POST",
                url: "services/tarefas.php",
                data: {recurso : recurso},
                beforeSend : function () {
                        console.log('Carregando AJAX else...');
                },
                success : function(retorno){
                    //traz o retorno do que pegou do php 
                    $( "recurso" ).show( "slow" );
                    document.getElementById('recurso_titulo').value = recurso;                        

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