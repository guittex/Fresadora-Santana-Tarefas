<?php
include_once("services/produto.php");
$produto_class = new produto();

if($_GET){
    $produto = $_GET['produto'];
    echo "<p id=cod_prod style=display:none> ".$produto."</p>";
}

if($_POST){
    $produto = $_POST['produto'];
    echo "<p id=cod_prod style=display:none> ".$produto."</p>";


}



//echo $produto;


?>

<style>

.titulo_tabela{
    font-weight: bold;
}

.line {
    width: 100%;
    height: 2px;
    border-bottom: 1px dashed dimgray;
    margin: 20px 0;
}

.form_tabela{
    border:none!important;
}

.label_tabela{
    font-size: 21px;
    font-weight: bold
}

input{
    font-size: 20px!important;
}



</style>

<!DOCTYPE html>
<html lang="pt-br">



<!--IMPORTACAO DO HEADER-->    
<?php
include_once("header.php");
?>


<body class="tes">



<!--IMPORTACAO DO MENU--> 

<div class="container-fluid">

    <div class="page-header">
    <img src='public/img/fresadora_logo.jpg' style='width:82px;position:absolute'>

        <h1 style="text-align: center;margin-bottom:50px;margin-top:20px">Produto - <?php echo $produto  ?></h1>
    </div>

    <table class="table table-bordered table-responsive" id='teste_table'>
        <thead>
        
        <tbody>

        <div class="row table-secondary" style='padding-bottom: 10px; padding-top: 30px;background: darkseagreen;'>

        <?php  $produto_class->listar_cliente($produto);?>



        </div>

        <div class="row table-secondary" style='padding-bottom: 30px; padding-top: 30px;'>


            <div class="col-12">
                

                <div id='master_detail'  class="row">
                    <div class="col-2">
                        <p class='label_tabela'>Operação:</p>
                        <input class="form-control form_tabela" readonly>
                    </div>
                    <div class="col-2">
                        <p class='label_tabela'>Máquina:</p>
                        <input class="form-control form_tabela" readonly>
                    </div>
                    <div  class="col-8">
                        <p class='label_tabela'>Descrição:</p>
                        <input class="form-control form_tabela w-100" readonly>
                    </div>
                </div>                    

            </div>

        </div>

        <div class="row table-primary" style='padding-bottom: 10px; padding-top: 0px;'>


            <div class="col-12">
                

                <div id=''  class="row">
                    <div class='col-12' style='margin-top:30px'>
                        <div class='form-group row'>
                            <p class='label_tabela'>OBS PCP:</p>
                        <div class='col-sm-10'>
                            <input class='form-control form_tabela' readonly>
                        </div>
                    </div>
                </div>                    

            </div>

        </div>
    
        </tbody>


        <thead>
            <tr>
                <th scope="col" class='titulo_tabela'>GL</th>
                <th scope="col" class='titulo_tabela'>Op</th>
                <th scope="col" class='titulo_tabela'>Setor</th>
                <th scope="col" class='titulo_tabela'>Recurso</th>
                <th scope="col" class='titulo_tabela'>Prog.Fabrica</th>
                <th scope="col" class='titulo_tabela'>Prev.Ini</th>
                <th scope="col" class='titulo_tabela' style='width: 250px;'>Apontamento</th>
                <th scope="col" class='titulo_tabela'>Coleta</th>
                <th scope="col" class='titulo_tabela'  style='width: 240px;'>Dt.PCP</th>
                <th scope="col" class='titulo_tabela'>Feito</th>
                <th scope="col" class='titulo_tabela'>Baixa FS</th>
                <th scope="col" class='titulo_tabela' style='width: 200px;'>Prev.FS</th>



            </tr>

        </thead>

        <tbody>
            
            <!--LISTAR AQUI A TABELA-->

            <?php  $produto_class->listar_produto($produto);
            ?>


            <!--FIM LISTAGEM-->
        </tbody>
    </table>

</div>


<!--IMPORTACAO FOOTER-->
<?php
include_once("footer.php");
?>




<script>
$('table tr').click(function(){

    var proc = $(this).find('.proc').html();
    var op = $(this).find('.op').html();
    var setor = $(this).find('.setor').html();
    console.log(proc);

    $.ajax({
        type: "POST",
        url: "services/ajax_produto.php",
        data: {proc : proc, op: op, setor: setor},
        beforeSend : function () {
                console.log('Carregando produto...');
        },
        success : function(retorno){
            //traz o retorno do que pegou do php 
            //alert(retorno);
            $("#master_detail").html(retorno);            
        },
                                
        error:function(data){
        }
    });


});



</script>



</body>
</html>