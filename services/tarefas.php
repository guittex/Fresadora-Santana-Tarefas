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
    public $produto;

            

    

    public function listar(){
        //pegando a data atual
        $data_atual = date('Y-m-d');   

        $datetime1 = date_create($data_atual);

        $dtAtual_convert = date("Y-m-d",strtotime($data_atual));

        //Define estilo padrão para os sticks
        $mudar_cor = "td_acabando";

        //chamando a função global para dentro do metodo
        global $funcoes;
        

        while($this->array = sqlsrv_fetch_array($this->query)){   
            
            
            $font_color = "black";
            $background = "white";
            $data_normal= $this->array['Prev_ini']->format('Y-m-d');
            $data_final = $this->array['DATA_FINAL']->format('Y-m-d');
            $apontamento = $this->array['APONTAMENTO']->format('Y-m-d H:i:s');
            $TOTAL_GERAL = $this->array['TOTAL_GERAL'];
        
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

            if($apontamento > ($data_atual-1)){
                $legenda = "<img src='public/img/icon/hand.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> " . $legenda ; //Apontado
                $background = "silver";
            }
            if($apontamento > $data_atual){
                //$legenda = "<img src='public/img/icon/hand.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> " . $legenda ; //Apontado
                $background = "yellow";
                
                
            }            
        
            echo "<tr style=color:$font_color;background:$background>"; 
            
            //Diferença da hora vendas clientes - Hora atual
            $datetime2 = date_create($data_final);
            $dias_vc = date_diff($datetime1, $datetime2);
            //$this->produto = $this->array['PRODUTO'];     

            echo "<td>" . "<a href=./pesquisar_produto.php?produto=" . $this->array['PRODUTO'] ."><p id='produto_tabela'>" . $this->array['PRODUTO'] .'-' .   $this->array['OPERACAO'] . "</p></a>" . '<p> DT.VC: ' . date('d-m', strtotime($data_final))  .'<span  id=data_cliente>' . $dias_vc->format('%R%a dias')  . '<span></p>'. '</br>' . $this->array['DESCRICAO'] . "</td>";
            echo "<td>" . $this->array['QTDE_TT'] . "</td>";
            //echo "<td style=width:400px; >" . $this->array['ACODI']."</td>";
            echo "<td style=width:400px; >" . $this->array['CLIENTE'] . $apontamento .  "</td>";

            //Diferença da hora da operação menos a data atual
            $datetime_op = date_create($data_normal);
            $dias_op = date_diff($datetime1, $datetime_op);
            $dias_faltam = $dias_op->format('%R%a dias');
            
            //Pega a data da operação e converte
            $dtOp_convert = date("Y-m-d",strtotime($data_normal));

            //Verifica se a data da operação é menor que a atual
            if($dtOp_convert < $dtAtual_convert){
                //Tem que ta vermelho os - dias
                $mudar_cor = "td_vermelha";

            }elseif( intval($dias_faltam) == 0 or intval($dias_faltam == 1)){
                $mudar_cor = "td_acabando";
            }else{
                $mudar_cor = "td_verde";
        
            }           

            echo "<td style=width:20%;  >" . '<p id=data_operacao>Dt.Prev: ' . date('d-m', strtotime($data_normal)) . ' <span id='.$mudar_cor.'>' . $dias_op->format('%R%a dias') . '</span>' . '</br>' . 'Hr.Prev: '.$funcoes->HrDecToString($TOTAL_GERAL) .   "</td>"; 
            
            echo "<td>" . '<span style=font-family:Wingdings;font-size:31px;>' . $legenda  .  '</span>' .  "</td>";
            echo "<td style=width:280px;>" . $this->array['PCP_OBS'] . "</td>";

            echo "</td>";

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
        echo        "<img src='public/img/icon/hourglass.png' style=width:40px;>  Prazo OS em menos de uma semana </br>";
        echo        "<img src='public/img/icon/keyboard.png' style=width:40px;>  PCP Reprogramado  </br>";     
        echo        "<img src='public/img/icon/hand.png' style=width:40px;>  Apontado </br>";
        echo        "<img src='public/img/icon/skull.png' style=width:40px;> Prazo OS Atrasado </br>";
        echo        "<img src='public/img/icon/bomb.png' style=width:40px;>  Foi reprogramado e já está atrasado </br>";
        echo    "</div>";
        echo    "<div class='modal-footer'>";
        echo        "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>";
        echo   "</div>";
        echo    "</div>";
        echo "</div>";
        echo "</div>";

    }

    function pesquisar_op(){
    
        echo "<div class='modal fade bd-example-modal-lg' id='pesquisar_modal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-lg' role='document'>";
        echo    "<div class='modal-content'>";
        echo        "<div class='modal-header'>";
        echo            "<h2 class='modal-title' id='modalTituloPesquisar'>Pesquisar</h2>";
        echo            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo            "<span aria-hidden='true'>&times;</span>";
        echo            "</button>";
        echo        "</div>";
        echo        "<form method=GET action=./pesquisar_produto.php>";
        echo            "<div class='modal-body'>";
        echo                "<input type=text class=form-control id=produto name='produto' placeholder='DIGITE A OS OU PRODUTO AQUI'>";         
        echo            "</div>";
        echo            "<div class='modal-footer'>";
        echo                "<button type='button' class='btn btn-danger' data-dismiss='modal'>Fechar</button>";
        echo                "<button type=submit class='btn btn-xs btn-dark'  value='Pesquisar'> Pesquisar</button>";
        echo            "</div>";
        echo        "</form>";
        echo    "</div>";  
        echo "</div>";
        echo "</div>";
    
    }

    function modal_certeza(){
 

        echo "<div class='modal fade bd-example-modal-lg' id='tem_certeza' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-md' role='document'>";
        echo    "<div class='modal-content'>";
        echo        "<div class='modal-header'>";
        echo            "<h3 class='modal-title' id='modalTituloPesquisar'>Tem certeza que deseja pesquisar este produto?</h3>";
        echo            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo            "<span aria-hidden='true'>&times;</span>";
        echo            "</button>";
        echo        "</div>";
        echo        "<form method=GET action=./pesquisar_produto.php style='margin:0 auto'>";
        //echo            "<div class='modal-body'>";
        //echo                "<input type=text class=form-control id=produto name='produto' placeholder='DIGITE A OS OU PRODUTO AQUI'>";         
        //echo            "</div>";
        echo            "<div class='modal-footer text-center'>";
        echo                "<button type='button' class='btn btn-danger    ' data-dismiss='modal'>Não</button>";
        echo                "<button type=button id='sim' class='btn btn-xs btn-dark'>Sim</button>";
        echo            "</div>";
        echo        "</form>";
        echo    "</div>";
        echo "</div>";
        echo "</div>";

  
    }

}

if($_POST){
    $tarefas = new tarefas();
    $recurso = $_POST['recurso'];
    
    $tarefas->pesquisar($recurso);
}



?>

