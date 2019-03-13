<?php
include_once("sql_conexao.php");   
include_once("funcoes.php");
$funcoes = new funcoes();



class tarefas extends conexao{

    public $query;
    public $rhs_setor;
    public $rhs_destinatario;
    public $rhs_serviço;
    public $array;  

            

    

    public function listar(){
        //pegando a data atual
        $data_atual = date('Y-m-d');

        //chamando a função global para dentro do metodo
        global $funcoes;
        

        while($this->array = sqlsrv_fetch_array($this->query)){   
            
            
            $font_color = "black";
            $background = "white";
            $data_normal= $this->array['Prev_ini']->format('Y-m-d');
            $data_final = $this->array['DATA_FINAL']->format('Y-m-d');
            $apontamento = $this->array['APONTAMENTO']->format('Y-m-d');
            $TOTAL_GERAL = $this->array['TOTAL_GERAL'];
            //print_r($TOTAL_GERAL);
        
            $legenda = '';
            if (!empty($this->array['PCPDATA_Ini'])){
                $data_normal= $this->array['PCPDATA_Ini']->format('Y-m-d');
                $legenda = $legenda .= "<img src='public/img/icon/keyboard.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; // TECLADO

                //echo "<td colspan='3'>" . $this->array['PCPDATA_Ini']->format('d-m-Y') . "</td>";                         
            }

            if ($data_final < $data_atual){
                $font_color = "red";
                $legenda = $legenda .= "<img src='public/img/icon/skull.png'data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; //Caveira
            }

            if($apontamento > $data_atual - 10){
                $legenda = "<img src='public/img/icon/hand.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> " . $legenda ; //Apontado
                $background = "yellow";

            }

            if($data_normal < $data_atual + 3 and $data_normal > $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/hourglass.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; // ampulheta
            }

            if($data_normal <= $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/bomb.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> ";//Bomba
            }            
        
            echo "<tr style=color:$font_color;background:$background>"; 
            echo "<td>" . "<p style=font-weight:bold;color:black;>" . $this->array['PRODUTO'] .'-' .   $this->array['OPERACAO'] . "</p>" . ' DATA.VC: ' . date('d-m-Y', strtotime($data_final))  . '</br>' . $this->array['DESCRICAO'] . "</td>";
            echo "<td>" . $this->array['QTDE_TT'] . "</td>";
            echo "<td style=width:300px; >" . $this->array['CLIENTE'] .  "</td>";
            echo "<td style=width:200px;  >" . date('d-M', strtotime($data_normal)) . '</br>' . 'Hr.Prev: '.$funcoes->HrDecToString($TOTAL_GERAL) .  "</td>";            //$this->convertHoras($this->array['TOTAL_GERAL'])
            echo "<td>" . '<span style=font-family:Wingdings;font-size:31px;>' . $legenda  .  '</span>' .  "</td>";
            echo "<td>" . $this->array['PCP_OBS'] . "</td>";
            //echo "<td> <a href=vizualizarModal.php?produto=" . $this->array['PRODUTO'] .   "</a><button type=button class='btn btn-xs btn-primary'> Visualizar</button></td>";

            echo "</td>";

            $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
            if ($SendPesqUser){
                echo "<th><i class='fas fa-print'  onClick='window.print()' style=cursor:pointer></i>";

            }
            echo "</tr>";
            }  
            
            
    }

    function pesquisar($recurso){    
        $this->sql_conexao("TAREFAS");

        if(empty($_POST['recurso'])){
            $recurso = 'TOR1';
            
        }        
        
        $data = date('Y-m-d');
        $data_maior = date('Y-m-d', strtotime($data. ' + 90 days'));
        $data_menor = date('Y-m-d', strtotime($data. ' - 90 days'));
        //PRINT_R($data_maior);
        //PRINT_R($data_menor);

        
        $sql_comparacao = " EXEC SP_Lista_de_tarefa '$recurso', '$data_menor' , '$data_maior'  "; 
        
        $this->query = sqlsrv_query($this->con, $sql_comparacao );
        
        
        $this->listar();
    
    }

    function legenda(){
        
        echo "<div class='modal fade bd-example-modal-lg' id='bomba_modal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-lg' role='document'>";
        echo    "<div class='modal-content'>";
        echo        "<div class='modal-header'>";
        echo            "<h5 class='modal-title' id='exampleModalLabel'>Legenda</h5>";
        echo            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo            "<span aria-hidden='true'>&times;</span>";
        echo            "</button>";
        echo        "</div>";
        echo    "<div class='modal-body'>";
        echo        "<img src='public/img/icon/skull.png' style=width:40px;> Foi reprogramado e já está atrasado </br>";
        echo        "<img src='public/img/icon/bomb.png' style=width:40px;>  Atrasado e não reprogramado </br>";
        echo        "<img src='public/img/icon/hand.png' style=width:40px;>  Atualmente apontado </br>";
        echo        "<img src='public/img/icon/hourglass.png' style=width:40px;>  Prazo de entrega em menos de uma semana </br>";
        echo        "<img src='public/img/icon/keyboard.png' style=width:40px;>  Foi reprogramado </br>";     
        echo    "</div>";
        echo    "<div class='modal-footer'>";
        echo        "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>";
        echo   "</div>";
        echo    "</div>";
        echo "</div>";
        echo "</div>";
    }

    public function vizualizarModal(){
        
    
        
    }
        
}

if($_POST){
    $tarefas = new tarefas();
    $recurso = $_POST['recurso'];
    
    $tarefas->pesquisar($recurso);
}



?>

