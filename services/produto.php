
<?php
date_default_timezone_set('America/Sao_Paulo');

include_once("tarefas.php"); 
include_once("sql_conexao.php"); 





class produto extends tarefas{

    public $sql_produto;
    public $query_produto;
    public $array_produto;


    
    public function listar_produto($produto){
        $this->sql_conexao("TAREFAS");

        $this->sql_produto = "SELECT * FROM fsmaster.ARQ31 WHERE GPROD = '$produto' AND IS_DELETED ='N' and GSETOR != ' '  ORDER BY GORDE ";

        $this->query_produto = sqlsrv_query($this->con, $this->sql_produto);

        //echo($this->sql_produto);
        $hora = date('H:i');
        $data_hoje = date('d-m H:i');

        $data_hoje2 = date('d-m'); 

        while($this->array_produto = sqlsrv_fetch_array($this->query_produto)){ 
            
            $background = "white";
            $font_color = "black";
            $display = "inherit";

            

            //PEGA DATA DA BAIXA, DA PREVISA E APONTAMENTO
            $baixa_fs = $this->array_produto['GDTBA']->format('Y-m-d') ;
            $previa_fs = $this->array_produto['GDTPR']->format('Y-m-d') ;
            $apontamento = $this->array_produto['PPIDT']->format('Y-m-d H:i') ;            

            //CONVERTE PARA INT
            $baixaFs_Convert = strtotime($baixa_fs);
            $previsaFs_Convert = strtotime($previa_fs);
            $apontamento_Convert = strtotime($apontamento);

            

            //VERIFICA SE A DATA DA BAIXA É MAIOR QUE 2000-01-01
            if($baixaFs_Convert >= 75600){
                //ATRIBIU A DATA O VALOR QUE VEM DO BANCO
                $baixa_fs = $this->array_produto['GDTBA']->format('d-m') ;

            }else{
                //DATA MENOR QUE 2000-01-01 RECEBE CAMPO VAZIO
                $baixa_fs = '';
            }  
            
            if($previsaFs_Convert >= 75600){
                $previa_fs = $this->array_produto['GDTPR']->format('d-m') ;

            }else{
                $previa_fs = '';
            }

            if($apontamento_Convert  >= 75600){
                
                //echo $apontamento;
                $data = new DateTime($apontamento);
                $data->modify('+10 hours');
                $dtFinalHr = $data->format('d-m H:i');
                //echo $data->format('d-m H:i');
                //echo $dtFinalHr;

                $apontamento = $this->array_produto['PPIDT']->format('d-m H:i') ;
                $apontamento2 =  $this->array_produto['PPIDT']->format('d-m')  ;        

            }else{
                $apontamento = '';
            }

            
            //echo $baixa_fs .' - ';

            if($baixa_fs != $data_hoje2 and $baixa_fs != ''){
                $font_color = "blue";

            }else{
                if($this->array_produto['PPIStatus'] == 'RETRABALHO' and $baixa_fs == NULL ){
                    $background = "red";
                    $font_color = "white";
                }

                if($data_hoje2 === $baixa_fs){
                    $background = "blue";
                    $font_color = "white";
                }
                

                if(!empty($dtFinalHr)){
                    if($apontamento <= $dtFinalHr and $apontamento != NULL and $apontamento2 == $data_hoje2){
                        $background = "yellow";
                        $font_color = "black";
                    }

                    if($apontamento2 !=  $data_hoje2 and $apontamento != NULL and $this->array_produto['PPIStatus'] != 'RETRABALHO' ){
                        $background = "darkgray";
                        $font_color = "black";
                    }
                }
            }
            
            
            
                echo    "<tr id='teste' style=color:$font_color;background:$background;>";
                echo        "<td>0</td>";
                echo        "<td class=op>". $this->array_produto['GORDE'] ."</td>";//OK
                echo        "<td class=setor>". $this->array_produto['GMAQU'] ."</td>";//OK
                echo        "<td>". $this->array_produto['PREAC_Recurso'] ."</td>"; //OK               
                echo        "<td>". $this->array_produto['GPROG'] ."</td>";//OK
                echo        "<td>0</td>";//OK
                echo        "<td>". $apontamento ."</td>";  //OK              
                
                echo        "<td>". $this->array_produto['PPIStatus'] ."</td>";    //OK           
                
                if($this->array_produto['PCPDATA'] == NULL or $this->array_produto['PCPDATA_Fim'] == NULL){
                echo        "<td></td>"; 
                }else{
                echo        "<td>". 'Dt. Ini: '. $this->array_produto['PCPDATA']->format('d-m') . '</br>'. 'Dt. Fim: ' . $this->array_produto['PCPDATA_Fim']->format('d-m') .     "</td>";            

                }

                        
                echo        "<td>0</td>"; 

                echo        "<td>". $baixa_fs ."</td>";    
                echo        "<td>". $previa_fs ."</td>";            
                echo        "<td class=proc style=display:none>". $this->array_produto['GPROC'] ."</td>";            
                echo    "</tr>";
            
        
        }
    }

        public function listar_cliente($produto){
            $this->sql_conexao("TAREFAS");
    
        
            $sql_cliente = "SELECT 
                TOP 1000

                ACODI     AS ID_CLIENTE ,
                ANOME     AS CLIENTE ,
                FPECA     AS PECA ,
                FAMOS     AS DESENHO ,
                FQTDE     AS QTDE  ,
                FDTPR     AS DT_PC ,
                FDTPR2    AS DT_PV ,
                FDTBA     AS DT_BAIXA ,
                F.PCP_OBS AS PCP_OBS,
                    * 

                FROM
                FS_NEW.FSMASTER.ARQ30 F
                INNER JOIN 
                FS_NEW.FSMASTER.ARQ01 C ON 
                F.FCLFO = C.ACODI 
                AND F.IS_DELETED ='N' 
                AND C.IS_DELETED ='N' 

                WHERE 
                FPROD = '$produto'   ";

            $query_cliente = sqlsrv_query($this->con, $sql_cliente);


            while($array_cliente = sqlsrv_fetch_array($query_cliente)){   

                echo    "<div class='col-12'>";
                echo            "<div class='form-group row'>";
                echo                "<p class='label_tabela'>Cliente:</p>";
                echo                "<div class='col'>";
                echo                "<input class='form-control form_tabela' value='". $array_cliente['CLIENTE']   ."' readonly>";
                echo            "</div>";
                echo         "<div class='col'>";
                echo            "<a href=index.php><button type='button' class='btn btn-primary'>Voltar</button></a>";
                echo            "</div>";
                

                echo        "</div>";
                echo    "</div>";

                echo    "<div class='col-2'>";
                echo        "<p class='label_tabela'>Qtde:</p>";
                echo        "<input class='form-control form_tabela' value='". $array_cliente['QTDE']   ."' readonly>";
                echo    "</div>";

                echo    "<div class='col-4'>";
                echo        "<p class='label_tabela'>Previsão:</p>";
                echo        "<input class='form-control form_tabela' value='". $array_cliente['DT_PV']->format('d-m-Y   ')   ."' readonly>";
                echo    "</div>";

                echo    "<div class='col-6'>";
                echo        "<div class='row'>";
                        
                echo            "<div class='col-6'>";
                echo                "<p class='label_tabela'>Ped.Cliente:</p>";
                echo                "<input class='form-control form_tabela' value='". $array_cliente['FPDCL']   ."' readonly>";
                echo            "</div>";
                echo            "<div class='col-6'>";
                echo                "<p class='label_tabela'>Processista:</p>";
                echo                "<input class='form-control form_tabela' value='". $array_cliente['OP_LIBERA']   ."' readonly>";
                echo            "</div>";
                echo        "</div>";
                echo    "</div>";
                echo    "<div class='col-12' style='margin-top:30px'>";
                echo        "<div class='form-group row'>";
                echo            "<p class='label_tabela'>Peça:</p>";
                echo        "<div class='col-sm-10'>";
                echo            "<input class='form-control form_tabela' value='". $array_cliente['PECA']   ."' readonly>";
                echo        "</div>";
                echo    "</div>";
                echo    "</div>";
            }
        }


    }




?>