<?php
include_once("sql_conexao.php");   


class tarefas extends conexao{

    public $query;
    public $rhs_setor;
    public $rhs_destinatario;
    public $rhs_serviço;
    public $array;              


    public function listar(){
        $data_atual = date('Y-m-d');


        while($this->array = sqlsrv_fetch_array($this->query)){   
            
            
            $font_color = "black";
            $background = "white";
            $data_normal= $this->array['Prev_ini']->format('Y-m-d');
            $data_final = $this->array['DATA_FINAL']->format('Y-m-d');
            $apontamento = $this->array['APONTAMENTO']->format('Y-m-d');

        
            $legenda = '';
            if (!empty($this->array['PCPDATA_Ini'])){
                $data_normal= $this->array['PCPDATA_Ini']->format('Y-m-d');
                $legenda = $legenda .= "<img src='public/img/icon/keyboard.png' style=width:40px;> "; // TECLADO

                //echo "<td colspan='3'>" . $this->array['PCPDATA_Ini']->format('d-m-Y') . "</td>";                         
            }

            if ($data_final < $data_atual){
                $font_color = "red";
                $legenda = $legenda .= "<img src='public/img/icon/skull.png' style=width:40px;> "; //Caveira
            }

            if($apontamento > $data_atual - 10){
                $legenda = "<img src='public/img/icon/hand.png' style=width:40px;> " . $legenda ; //Apontado
                $background = "yellow";

            }

            if($data_normal < $data_atual + 3 and $data_normal > $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/hourglass.png' style=width:40px;> "; // ampulheta
            }

            if($data_normal <= $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/bomb.png' style=width:40px;> ";//Bomba
            }            
        
            echo "<tr style=color:$font_color;background:$background>";
            echo "<td>" . "<p style=font-weight:bold;color:black;>" . $this->array['PRODUTO'] .'-' .   $this->array['OPERACAO'] . "</p>" . ' VC: ' . date('d-m-Y', strtotime($data_final))  . '</br>' . $this->array['DESCRICAO'] . "</td>";
            echo "<td>" . $this->array['QTDE_TT'] . "</td>";
            echo "<td style=width:300px; >" . $this->array['CLIENTE'] .  "</td>";
            echo "<td style=width:200px;  >" . date('d-M', strtotime($data_normal)) . '</br>' . "</td>";            
            echo "<td>" . '<span style=font-family:Wingdings;font-size:31px;>' . $legenda  .  '</span>' .  "</td>";
            echo "<td>" . $this->array['PCP_OBS'] . "</td>";



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
        
}

if($_POST){
    $tarefas = new tarefas();
    $recurso = $_POST['recurso'];
    
    $tarefas->pesquisar($recurso);
}



?>

