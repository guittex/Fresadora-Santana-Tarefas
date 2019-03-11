
<?php
include_once("services/sql_conexao.php");
$conexao = new conexao($database);

$conexao->sql_conexao("TAREFAS");

$produto =  $_GET['produto'];

$result_usuario = "EXEC SP_Lista_de_tarefa 'TOR1','20181001' ,'20190220' ";
$resultado_usuario = sqlsrv_query ($conexao->con, $result_usuario);
$row_usuario = sqlsrv_fetch_array($resultado_usuario);

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
<div class="container theme-showcase" role="main">
<?php
print_r($produto);
?>
    <!--TITULO LISTAR RHS-->
    <div class="page-header">
        <h1 style="text-align: center;">Produto</h1>
    </div>


    <div class="row" style="display: inherit; margin-top: 40px">
        <div class="col-12">
        
            <form method="POST">
            
                <input type="hidden" name="id" value="<?php echo $row_usuario['PRODUTO'];?>">
                
                <div class="form-group">
                
                    <div class="col-12">
                        <label for="exampleInputEmail1">Produto</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" style="font-size: 25px;" value="<?php echo $row_usuario['PRODUTO'];?> " disabled>
                    </div>

                    <div class="col-12">
                        <label for="exampleInputEmail1">Descrição</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" style="font-size: 25px;" value="<?php echo $row_usuario['DESCRICAO'];?>" disabled >
                    </div>



                </div>

                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>
    </div>


</div>

</div>

<!--IMPORTACAO FOOTER-->
<?php
include_once("footer.php");
?>

<script src="public/js/modal_apagar.js"></script>

<script src="public/js/bootstrap-4.1.js"></script>

</body>
</html>