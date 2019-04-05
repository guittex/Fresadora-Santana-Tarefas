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
    public $produto_apontado;
    public $atividades;
    public $background_atv;
    public $color_atv;

    public function listar($recurso){
        //$this->getApontamento();



        $produto_apontado = $this->produto_apontado['Produto'];
        $operacao_apontado = $this->produto_apontado['Operacao'];

        //echo $operacao_apontado;


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
            $multa = $this->array['MULTA'];

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

            if($data_normal < $data_atual + 3 and $data_normal > $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/hourglass.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; // ampulheta
                
            }

            if($data_normal <= $data_atual){
                $legenda = $legenda .= "<img src='public/img/icon/bomb.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> ";//Bomba
            }    

            if($produto_apontado == $this->array['PRODUTO'] and $operacao_apontado  == $this->array['OPERACAO'] ){
                //$legenda = "<img src='public/img/icon/hand.png' data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> " . $legenda ; //Apontado
                $background = "yellow";        
                //echo $operacao_apontado  ;
                //echo $produto_apontado;
                
            }           
    

        
            echo "<tr style='color:$font_color;background:$background'>"; 
            
            //Diferença da hora vendas clientes - Hora atual
            $datetime2 = date_create($data_final);
            $dias_vc = date_diff($datetime1, $datetime2);
            $diasVc_falta = $dias_vc->format('%R%a dias');

            //Se a diferença for maior que 1 tudo verde
            if($diasVc_falta > 1){
                $mudar_cor = "td_verde";
            }
            //Se a diferença for menor que 0 tudo vermelha
            if($diasVc_falta < 0){
                $mudar_cor = "td_vermelha";
            }
            //Se a diferença for igual a 0 ou igual a 0 ou 1
            if($diasVc_falta == 0 or $diasVc_falta == 1){
                $mudar_cor = "td_acabando";
            }
            
            //Pegando o recurso selecionado 
            global $recurso;
            $OS = $this->array['OS'];
            
            echo "<td style=width:20%;><a href=./pesquisar_produto.php?produto=" . $this->array['PRODUTO'] ."&recurso=".$recurso."><p id='produto_tabela'>" . $this->array['PRODUTO'] .'-' .   $this->array['OPERACAO'] . "</p></a>" . '<p> DTVC: ' . date('d-m', strtotime($data_final))  .'<span  id='.$mudar_cor.'>' .  $dias_vc->format('%R%a dias')  . '</span></p>' . '<p>DTPV:'.$this->array['DATA_PV']->format('d-m-Y'). "</p></td>";

            //echo "<td>" . $this->array['QTDE_TT'] . "</td>";
            //echo "<td style=width:400px; >" . $this->array['ACODI']."</td>";
            
            if($multa == 1){
                $legenda_multa =  "<img src='public/img/icon/dolar.png'data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; //Caveira
                $legenda = $legenda .= "<img src='public/img/icon/dolar.png'data-toggle='modal' data-target='#bomba_modal' style=width:40px;cursor:pointer;> "; //Caveira

            }else{
                $legenda_multa = '';
            }
        
            echo "<td style=width:438px; > <p> ". $legenda_multa . $this->array['CLIENTE'] .  "</p><p>" . $this->array['DESCRICAO'] . " </p><p style='1px solid;background:blue;color:white;border-radius:10px'>" .$this->array['PCP_OBS'] . "</p><p  style=background:orange;color:black;font-weight:bold;border-radius:10px>" .$this->array['LOCALIZACAO']. "</p></td>";
            
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

            //Verifica  se os dias que faltam é igual a 0 ou igual a 1  
            }elseif( intval($dias_faltam) == 0 or intval($dias_faltam == 1)){
                $mudar_cor = "td_acabando";
            }else{
                $mudar_cor = "td_verde";
        
            }           

            echo "<td style=width:20%;  >" . '<p id=data_operacao>Dt.Prev: ' . date('d-m', strtotime($data_normal)) . ' <span id='.$mudar_cor.'>' . $dias_op->format('%R%a dias') . '</span></p>' .  '<p>Hr.Prev: '.$funcoes->HrDecToString($TOTAL_GERAL) .   "</p><p> QTDE: " .$this->array['QTDE'].  "</p></td>"; 
            
            echo "<td style=width:10%;>" . '<span style=font-family:Wingdings;font-size:31px;>' . $legenda  .  '</span>' .  "</td>";


            echo "</td>";

            echo "</tr>";
            }  


            //PEGA O CAMP ATIVIDADE DO BANCO E COLOCA NO TITULO 
            echo "<script>document.getElementById('atividade').value = '$this->atividades'; </script>";

        
            if(empty($this->produto)){
                //echo "red";
                $this->background_atv = 'red';
               // $this->color_atv = 'white';
                //echo $this->color_atv;
                echo "<script>document.getElementById('linha_atividade').style.background= 'red';</script>";
                echo "<script>document.getElementById('atividade').style.color= 'white';</script>";
                echo "<script>document.getElementById('label_tabela').style.color= 'white';</script>";


            }else{
                $this->background_atv = "yellow";
                //$this->color_atv = "black";
                echo "<script>document.getElementById('linha_atividade').style.background= 'yellow';</script>";
                echo "<script>document.getElementById('atividade').style.color= 'black';</script>";
                echo "<script>document.getElementById('label_tabela').style.color= 'black';</script>";


                //echo "yellow";
            }
    
            
            
            
    }

    function pesquisar($recurso){    
        $this->sql_conexao("TAREFAS");
        //echo "to na tarefas - " . $recurso;        
        
        $data = date('Y-m-d');
        $data_maior = date('Y-m-d', strtotime($data. ' + 90 days'));
        $data_menor = date('Y-m-d', strtotime($data. ' - 90 days'));
        //PRINT_R($data_maior);
        //PRINT_R($data_menor);
        
        $sql_comparacao = " EXEC SP_Lista_de_tarefa '$recurso', '$data_menor' , '$data_maior'  "; 
        
        $this->query = sqlsrv_query($this->con, $sql_comparacao );        
        


        $this->listar($recurso);
    
    }

    public function legenda(){
        
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
        echo        "<img src='public/img/icon/hourglass.png' style=width:40px;margin:10px;>  Prazo OS em menos de uma semana </br>";
        echo        "<img src='public/img/icon/keyboard.png' style=width:40px;margin:10px;>  PCP Reprogramado  </br>";     
        echo        "<img src='public/img/icon/hand.png' style=width:40px;margin:10px;>  Apontado </br>";
        echo        "<img src='public/img/icon/skull.png' style=width:40px;margin:10px;> Prazo OS Atrasado </br>";
        echo        "<img src='public/img/icon/bomb.png' style=width:40px;margin:10px;>  Foi reprogramado e já está atrasado </br>";
        echo        "<img src='public/img/icon/dolar.png' style=width:40px;margin:10px;>  Multa </br>";

        echo    "</div>";
        echo    "<div class='modal-footer'>";
        echo        "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>";
        echo   "</div>";
        echo    "</div>";
        echo "</div>";
        echo "</div>";

    }

    public function pesquisar_op(){
        global $recurso;
        //echo $recurso;
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
        echo                "<input type=text class=form-control id=produto name='produto' placeholder='DIGITE O PRODUTO AQUI'>"; 
        echo                "<input type=hidden class=form-control  id=recurso name='recurso' value=". $recurso .">";
        //echo                "<input type=text class=form-control  id=recurso name='recurso' value=". $OS .">";
        echo            "</div>";
        echo            "<div class='modal-footer'>";
        echo                "<button type='button' class='btn btn-danger' id='btn_geral' data-dismiss='modal'>Fechar</button>";
        echo                "<button type=submit class='btn btn-xs btn-success' id='btn_geral'  value='Pesquisar'> Pesquisar</button>";
        echo            "</div>";
        echo        "</form>";
        echo    "</div>";  
        echo "</div>";
        echo "</div>";
    
    }   

    public function razaoDo_nome(){
        global $recurso;
        //echo $recurso;
        /*
        echo "<div class='modal fade bd-example-modal-lg' id='razaoDo_nome' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-lg' role='document'>";
        echo    "<div class='modal-content'>";
        echo        "<div class='modal-header'>";
        echo            "<h2 class='modal-title' id='modalTituloPesquisar'><span style=font-weight:bold>Significado do nome MR. Chicken</span></h2>";
        echo            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo            "<span aria-hidden='true'>&times;</span>";
        echo            "</button>";
        echo        "</div>";
        echo            "<div class='modal-body'>";
        echo                "<p>Você acabou de descobrir um de nossos Easter Egg do sistema</p>";
        echo                "<p><span style=font-weight:bold>O significado de Mr.Chicken:</span> Vem de um antigo supervisor fofoqueiro de um de nossos funcionários
                                aonde ele trabalhava antigamente que se chamava 'Zé Galinha', sendo assim, apelidamos nosso sistema como 'Zé Galinha' 
                                por conta que o sistema cagueta o que cada recurso está fazendo e se está parado ou não</p>"; 
        echo                "<p> O nome Mr. Chicken dado é apenas um cod-nome para ocultar realmente o verdadeiro significado  </p>";                           
        echo            "</div>";
        echo            "<div class='modal-footer'>";
        echo                "<button type='button' class='btn btn-danger' id='btn_geral' data-dismiss='modal'>Fechar</button>";
        echo            "</div>";
        echo    "</div>";  
        echo "</div>";
        echo "</div>";*/
    
    }   

    public function selecionar_setor(){
        echo "<div class='modal fade bd-example-modal-lg' id='selecionar_setor' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-lg' role='document'>";
        echo    "<div class='modal-content'>";
        echo        "<div class='modal-header'>";
        echo            "<h2 class='modal-title' id='modalTituloPesquisar'><span style=font-weight:bold>Atenção!</span></h2>";
        echo            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo            "<span aria-hidden='true'>&times;</span>";
        echo            "</button>";
        echo        "</div>";
        echo            "<div class='modal-body'>";
        echo                "<p>Selecione o setor para abrir o Painel</p>";                             
        echo            "</div>";
        echo            "<div class='modal-footer'>";
        echo                "<button type='button' class='btn btn-danger' id='btn_geral' data-dismiss='modal'>Fechar</button>";
        echo            "</div>";
        echo    "</div>";  
        echo "</div>";
        echo "</div>";
    
    }   

    public function getApontamento(){
        $this->sql_conexao("TAREFAS");

        global $recurso;
        //echo $recurso;

        $sql= " EXEC FS_NEW.DBO.SP_GetApontamento '$recurso' ";           

            $query = sqlsrv_query($this->con,$sql);

            $this->produto_apontado = sqlsrv_fetch_array($query);            

            $this->atividades = $this->produto_apontado['ATIVIDADE'];  
            
            $this->produto = $this->produto_apontado['Produto'];          
            
            //$this->atividades = str_replace('AGUARDANDO APONTAMENTO', ' ', trim($this->atividades));

    }


    public function painel_listar($setor,$titulo){
        global $funcoes;


        $this->sql_conexao("TAREFAS");
        include_once("ajax_setor.php");       
    
        while($array2 = sqlsrv_fetch_array($query_recurso)){
            
            $arrayDosRecursos = $array2['ID_RECURSO'];

            $sql= "EXEC FS_NEW.DBO.SP_GetApontamento '$arrayDosRecursos' ";

            $query = sqlsrv_query($this->con,$sql);

            $this->produto_apontado = sqlsrv_fetch_array($query);            

            //Valore vindo do sql
            $this->atividades = $this->produto_apontado['ATIVIDADE'];
            $tempo = $this->produto_apontado['HR_SALDO'];


            //Convertendo hora decimal para string
            $tempo_convertido = $funcoes->HrDecToString($tempo);    

            //Background e Cor do tempo do painel            
            $background_hr = "red";
            $color_hr = "white";

            //Cores do tempo painel de apontamentos
            if($tempo_convertido > 0){
                $background_hr = "";
                $color_hr = "white";
            }

            $background = 'white';
            $pisca = "padrao";
            $color = 'black';
            $img = "";
            $width = "40px";
            $icon = "";

            //Cores do corpo da tabela do painel
            if($this->produto_apontado['Produto'] <= " "){
                $background = $this->produto_apontado['Color_Back'];
                $color = $this->produto_apontado['Color_Font'];
                $icon = $this->produto_apontado['Imagem'];
                $img = "./public/img/icon/".$icon." ";
                $pisca = 'fa-blink';
            }elseif($this->produto_apontado['Produto'] > " " and $tempo_convertido > 0){
                $background = "lawngreen";
                $img = "./public/img/icon/time_green.png";
                $width = "40px";

            }else{
                $background = "lawngreen";
                $color = "red";
                $img = "./public/img/icon/time_red.png";
                $width = "40px";

            }

            // echo '<p style=background:yellow><span  style=color:black;font-weight:bold;>ATIVIDADES:</span>'. $this->atividades . '</p></br>';
            if(!empty($titulo)){
            echo    "<script>document.getElementById('titulo_painel').innerHTML='".$titulo."';</script>";
            }

            echo    "<div class='col-lg-4 col-md-6 col-sm-12 col-12'>";    
            echo        "<table class='table table-bordered'>";
            echo            "<thead class='thead-dark'>";
            echo                "<tr>";
            echo                    "<th scope='col'>". $this->produto_apontado['CodRecurso'] . '<span  style=background:'.$background_hr.';color:'.$color_hr.';float:right>'.$tempo_convertido.'</span>' .  "</th>";    
            echo                "</tr>";
            echo            "</thead>";
            echo            "<tbody>";
            echo                "<tr>";        
            echo                    "<td class=".$pisca." style='background:".$background.";color:".$color.";border-color:black;'>". $this->atividades . "<img style=width:".$width." src=".$img.">" . "</td>";  
            echo                "</tr>";
            echo            "</tbody>";
            echo        "</table>";
            echo    "</div>";

        }   
        
        unset($sql);
        unset($array2);



    }

}

if($_POST){
    $tarefas = new tarefas();
    
    $recurso = $_POST['recurso'];    


    $tarefas->getApontamento();

    $tarefas->pesquisar($recurso);

}






?>

