<?php
include_once("services/sql_conexao.php");
include_once("services/tarefas.php");

$tarefas = new tarefas();

$con = new conexao();

$con->sql_conexao("TAREFAS");

$setorget = $_GET['setor'];

$titulo = $_GET['titulo'];

/*if($titulo == 'Tornos Mecânicos'){
    
    $titulo = 'Tornos Mecanicos';
    header("Refresh: 30; url = painel_index.php?setor=$setorget&titulo=Tornos%20Mecanicos "); 

}else{
    header("Refresh: 30; url = painel_index.php?setor=$setorget&titulo=$titulo "); 

}*/

$porta = $_SERVER['SERVER_PORT'];

session_start();

if (isset($_SESSION["login"])) { 
    $porta = 8086;
    //echo $_SESSION['login'];
}

if ( $porta != 8086){

    echo
    "<script>   
        alert('Necessário entrar primeiro!');
    </script>";    
    header('Location: login.php'); 

    
}

?>

<script language="javaScript">	
    //Defini o valor do tempo para refresh e troca; 
    var min, seg;		min = 525600; seg=0;
        function relogio(){		          

            if((min > 0) || (seg > 0)){				
                if(seg == 0){					
                    seg = 59;					
                    min = min - 1	
                }				
                else{					
                    seg = seg - 1;				
                }				
                if(min.toString().length == 1){					
                    min = "0" + min;				
                }				
                if(seg.toString().length == 1){					
                    seg = "0" + seg;				
                }				
                document.getElementById('relogio').innerHTML = min + ":" + seg;				
                setTimeout('relogio()', 1000);			
            }			
            else{				
                document.getElementById('relogio').innerHTML = "00:00";
                var teste = teste;
                $.ajax({
                    type: "POST",
                    url: "services/ajaxTrocaPainel_automatico.php",
                    data: {teste : teste },
                    beforeSend : function () {
                            console.log('Carregando Setor teste...');
                    },
                    success : function(retorno){
                        //traz o retorno do que pegou do php 
                        //alert(retorno);                    
                        $("#linha_painel").html(retorno);      
                        
                    },
                                            
                    error:function(data){
                    }
                });	
                //alert("Acabou");		
            }	
        } 

</script>

<style>
@keyframes fa-blink {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 0; }
}
.fa-blink {
-webkit-animation: fa-blink 2s linear infinite;
-moz-animation: fa-blink 2s linear infinite;
-ms-animation: fa-blink 2s linear infinite;
-o-animation: fa-blink 2s linear infinite;
animation: fa-blink 2s linear infinite;
}

    .sidenav {
    height: 100%;
    width: 160px;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: #d1dce7;
    overflow-x: hidden;
    padding-top: 20px;
    }

    .sidenav a {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    }

    .sidenav a:hover {
    color: #f1f1f1;
    }

    .main {
    margin-left: 160px; /* Same as the width of the sidenav */
    font-size: 28px; /* Increased text to enable scrolling */
    padding: 0px 10px;
    }

    @media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
    }
</style>

<!--IMPORTACAO DA HEAD-->    
<?php
include_once("header.php");
?>

<html>
<!--Relogio contador-->
<div id='relogio' style=display:none></div>

<!--
<div class="sidenav">
    <div class="maqParada">
        <?php //$tarefas->maqParadaListar(); ?>
    </div>
</div>-->

<body class='tes' id="tes" onload=relogio()>

<div id="msg" ></div>

<div class="container-fluid" style=margin-bottom:100px>

    <div class="text-center" style=margin:30px>
        <!--Cabeçalho-->
        <div class="row">
            <!--<div class="col-2">
                <img id="mrcock" src="public/img/MrChicken.png" data-toggle='modal' data-target='#razaoDo_nome' style='width: 186px;border-radius: 25px;filter: drop-shadow(0px 12px 6px #00001E);margin:0!important'>
                
            </div> -->           
            <div class="col-3">
            
                <img id="btn_desligar" src="public/img/desligar.png" style=display:none>
                <img id="btn_ligar" src="public/img/ligar.png" style=display:none>                
                
                <a href=index.php><img id="btn_voltar" src="public/img/voltar.png"></a>
            </div>
            <div class="col-5">        
                <h1 id="titulo_painel"  style='font-weight: bold;color: white;'><?php echo $titulo ?></h1>
            </div>
            <div class="col-4">
                <select name='setor' id='select_setor' class='form-control form-control-lg' style='background:white;color:black!important'>
                    <option disabled selected>Setor</option>                
                </select>           
            </div>
        </div>

    </div>

    <div class="row" id="linha_painel">
        
        <?php $tarefas->painel_listar('',''); ?>        

    </div>

    <div class="row" style='float:right'>
        <button type="button" id='btn_ativa' class="btn btn-success" style="height: 51px;font-size: 19px;margin: 22px;">Ativar Automático</button>
        <button type="button" id='btn_desativa' class="btn btn-danger" style="height: 51px;font-size: 19px;margin: 22px;display:none">Desativar Automático</button>
    </div>

</div>

<script src="public/js/bootstrap-4.1.js"></script>

<script type="text/javascript">	

$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: "services/ajaxTroca_painel.php",
        data: {},
        beforeSend : function () {
                console.log('Carregando Setor...');
        },
        success : function(retorno){
            //traz o retorno do que pegou do php 
            //alert(retorno);                                     
            $("#select_setor").html(retorno);
            
        },
                                
        error:function(data){
        }
    });

    $('#select_setor').on('change',function(){
        troca_setor = document.getElementById("select_setor").value;
        troca_TITULO = $('#select_setor').find(":selected").text();
        console.log(troca_TITULO);
        console.log(troca_setor);

        window.location.href="painel_index.php?&setor="+ troca_setor+"&titulo=" + troca_TITULO;

    });

});

    //Função refresh, habilita automatico ou nao.
    $( "#btn_ativa" ).click(function() {
        document.getElementById("btn_desligar").style.display = "inherit";
        document.getElementById("btn_desativa").style.display = "inherit";
        document.getElementById("btn_ativa").style.display = "none";


    });

    $( "#btn_desativa" ).click(function() {
        var teste = document.getElementById("btn_ligar").style.display; 
        if(teste == "inherit"){
            document.getElementById("btn_ligar").style.border = "1px solid red";
            alert("Necessário desativar botão");
        }else{
            document.getElementById("btn_desligar").style.display = "none";
            document.getElementById("btn_ligar").style.display = "none";
            document.getElementById("btn_desativa").style.display = "none";
            document.getElementById("btn_ativa").style.display = "inherit";  
            document.getElementById("btn_ligar").style.border = "none";

        }       
    });
    
    var myVar = "";
    $( document ).ready(function() {
        myVar = setTimeout(function() {
                setor_titulo = document.getElementById("titulo_painel").innerHTML;
                setor_cod = "<?php echo $setorget; ?> ";                
                window.location.href="painel_index.php?&setor="+ setor_cod.trim()+"&titulo=" + setor_titulo;
                document.getElementById("tes").style.background = "linear-gradient(to right, #b6d4df,#0a0301,#5bc0de)";
            }, 40000);
    
        $( "#btn_desligar" ).click(function() {
            document.getElementById("btn_desligar").style.display = "none";
            document.getElementById("btn_ligar").style.display = "inherit";
            seg = 20;
            min = 0;
            //alert("nao tem que atualizar");
            clearTimeout(myVar);
        });            

        $( "#btn_ligar" ).click(function() {
            document.getElementById("btn_ligar").style.display = "none";
            document.getElementById("btn_desligar").style.display = "inherit";
            min = 525600;
            //alert("tem que atualizar");
            myVar = setTimeout(function() {
                setor_titulo = document.getElementById("titulo_painel").innerHTML;
                setor_cod = "<?php echo $setorget; ?> ";                
                window.location.href="painel_index.php?&setor="+ setor_cod.trim()+"&titulo=" + setor_titulo;
                document.getElementById("tes").style.background = "linear-gradient(#00BFFF,#010101)";
            }, 40000);
        });
    });
	
</script>

</body>
</html>