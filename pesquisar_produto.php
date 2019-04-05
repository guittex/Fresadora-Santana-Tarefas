<?php
include_once("services/produto.php");

include_once("services/tarefas.php");

$tarefas = new tarefas();

$produto_class = new produto();

//unset($_SESSION["newsession"]);

/*session_start();

echo ($_SESSION["newsession"][]);*/

global $recurso ;

if($_GET){
    $produto = $_GET['produto'];
    $recurso = $_GET['recurso'];    

    if(!empty($_GET['recurso'])){
        $recurso = $_GET['recurso'];
    }
    
    //echo $recurso;
    echo "<p id=cod_prod style=display:none> ".$produto."</p>";
}

if($_POST){
    $produto = $_POST['produto'];
    $recurso = $_POST['recurso'];
    
    echo "<p id=cod_prod style=display:none> ".$produto."</p>";


}


?>

<!--IMPORTACAO DO HEADER-->    

<?php
include_once("header.php");

?>

<!DOCTYPE html>
<html lang="pt-br">

<body class="tes" style='background: white!important;'>

    <div class="container-fluid">

        <div class="page-header">

        <div class="row">
            <div class="col-2">
                <img id="logo_fresadora" src='public/img/fresadora_logo.jpg'>
            </div>

            <div class="col-6">
                <div class="col-12">
                    <h1 style="text-align: center;margin-top:20px">Produto - <?php echo "<span class='produto_titulo'>". $produto . "</span>"  ?></h1>
                    <h1 style="text-align: center;margin-bottom:50px;">OS - <input name=input_os class='os_titulo' readonly> </h1>
                    
                </div>
            </div>

            <div class="col-4" style=margin-top:20px;>
                <a href=index.php?recurso=<?php echo $recurso ?> > <button type='button' id="btn_geral" class='btn btn-primary'>Voltar</button></a>
                <button type='button' class='btn btn-primary' style='margin-left: 5px;' id="btn_geral" data-toggle='modal' data-target='#pesquisar_modal'>Pesquisar</button>

            </div>
        </div>

        </div>

        <table class="table table-bordered table-responsive" id='teste_table'>
            <thead>
            
            <tbody>

            <div class="row table-secondary" style='padding-bottom: 10px; padding-top: 30px;background: darkseagreen;padding-left: 18px;'>

                <!--Chamando a listagem de produto passando o produto em parametros-->
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
                            <input class="form-control form_tabela w-100" id=input_descricao readonly>
                        </div>
                    </div>                    

                </div>

            </div>

            <div class="row table" style='padding-bottom: 10px; padding-top: 0px;background:blue;color: white'>

                <div class="col-12">                

                    <div class="row">
                        <div class='col-12' style='margin-top:30px'>
                            <div class='form-group row'>
                                <p class='label_tabela'>OBS PCP:</p>
                                <div class='col-sm-10'>
                                    <input name=input_pcp class='form-control form_tabela' readonly style=color:black>
                                </div>
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
                    <!--<th scope="col" class='titulo_tabela'>Descricao</th>-->
                    <th scope="col" class='titulo_tabela'>Setor</th>
                    <th scope="col" class='titulo_tabela'>Recurso</th>
                    <th scope="col" class='titulo_tabela'>Prog.Fabrica</th>
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

                <?php  $produto_class->listar_produto($produto);  ?>

                <!--FIM LISTAGEM-->

            </tbody>

        </table>

    </div>

<!-- MODAIS -->
<?php
$tarefas->pesquisar_op();

?>
<!----------->



<script src="public/js/bootstrap-4.1.js"></script>  


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

    $("#teste_table > tbody > tr").on("click", function (e) {
        $(this).siblings().removeClass("colorir");
        $(this).toggleClass("colorir");
    });


    var pcp_obs =document.getElementById("pcp_obs").value
    document.querySelector("[name='input_pcp']").value = pcp_obs;

    var os_titulo =document.getElementById("os_input").value
    document.querySelector("[name='input_os']").value = os_titulo;


    </script>



</body>
</html>