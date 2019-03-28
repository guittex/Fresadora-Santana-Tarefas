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
        
            echo "<td style=width:438px; > <p> ". $legenda_multa . $this->array['CLIENTE'] .  "</p><p>" . $this->array['DESCRICAO'] . " </p><p style='1px solid;background:blue;color:white;border-radius:10px'>" .$this->array['PCP_OBS'] . "</p><p style=background:orange;color:black;font-weight:bold;border-radius:10px>" .$this->array['LOCALIZACAO']. "</p></td>";
            
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

    public function getApontamento(){
        $this->sql_conexao("TAREFAS");

        global $recurso;
        //echo $recurso;

        $sql= "
                SELECT 
                    TOP 1
                    A.Produto    ,
                    Operacao   ,
                    Inicio     ,
                    G.PREAC_Recurso ,
                    CodRecurso ,
                    CodParada  ,
                    --P.DESCRICAO,
                    (
                    CASE WHEN ISNULL( A.PRODUTO,'')='' THEN 
                        '*PARADA* '
                    ELSE
                        'TRABALHANDO '
                    END
                    +
                    CONVERT(VARCHAR(5), (GETDATE()-INICIO) ,108)+' Hrs '+
            
                CASE WHEN ISNULL( A.PRODUTO,'')='' THEN 
                    P.DESCRICAO
                ELSE
                    'Prod.'+A.Produto+' - '+cast(operacao as varchar(10)) +
                    ' '+CAST( ANOME AS VARCHAR(20))
                END 
                )
                AS ATIVIDADE
                    
            
                        
                FROM
                    COLETA.dbo.TAB_Apontamento A
                    LEFT JOIN 
                    COLETA.dbo.TAB_Codpar  P ON 
                    A.CodParada  = P.CODPAR
                
            
                LEFT JOIN 
                    FS_NEW.FSMASTER.ARQ31 G ON 
                    ISNULL(A.CodParada ,'') ='' AND 
                    G.GPROD = A.PRODUTO AND
                    G.GORDE = A.Operacao 
            
                LEFT JOIN 
                    FS_NEW.FSMASTER.ARQ30 F ON 
                    ISNULL(A.CodParada ,'') ='' AND 
                    G.GPROD = F.FPROD  AND
                    F.IS_DELETED ='N' 
                
                LEFT JOIN 
                    FS_NEW.FSMASTER.ARQ01 C ON 
                    ISNULL(A.CodParada ,'') ='' AND 
                    F.FCLFO  = C.ACODI  AND
                    C.IS_DELETED ='N' 
            
            
            WHERE
                CodRecurso = '$recurso'
            
            ORDER BY 3 DESC";

            $query = sqlsrv_query($this->con,$sql);

            $this->produto_apontado = sqlsrv_fetch_array($query);            

            $this->atividades = $this->produto_apontado['ATIVIDADE'];  
            
            $this->produto = $this->produto_apontado['Produto'];  

           
            
            //$this->atividades = str_replace('AGUARDANDO APONTAMENTO', ' ', trim($this->atividades));

    }

}

if($_POST){
    $tarefas = new tarefas();

    $recurso = $_POST['recurso'];

    $tarefas->getApontamento();

    $tarefas->pesquisar($recurso);

}






?>

