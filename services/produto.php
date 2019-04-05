
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

        

        $this->sql_produto = "SELECT * FROM fsmaster.ARQ31 WHERE GPROD = '$produto' AND IS_DELETED ='N'  ORDER BY GORDE ";

        $this->query_produto = sqlsrv_query($this->con, $this->sql_produto);

        

        //echo($this->sql_produto);
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

            

            //VERIFICA SE A DATA DA BAIXA É MAIOR QUE 1969-01-01
            if($baixaFs_Convert >= 75600){
                //ATRIBIU A DATA O VALOR QUE VEM DO BANCO
                $baixa_fs = $this->array_produto['GDTBA']->format('d-m') ;

            }else{
                //DATA MENOR QUE 1969-01-01 RECEBE CAMPO VAZIO
                $baixa_fs = '';
            }  
            
            if($previsaFs_Convert >= 75600){
                $previa_fs = $this->array_produto['GDTPR']->format('d-m') ;

            }else{
                $previa_fs = '';
            }

            if($apontamento_Convert  >= 75600){
                
                //PEGA DATA DO APONTAMENTO E ACRESCENTA MAIS 10 HORAS
                $data = new DateTime($apontamento);
                $data->modify('+10 hours');
                $dtFinalHr = $data->format('d-m H:i');
                

                $apontamento = $this->array_produto['PPIDT']->format('d-m H:i') ;
                $apontamento2 =  $this->array_produto['PPIDT']->format('d-m')  ;        

            }else{
                $apontamento = '';
            }

            
            //SE A BAIXA FOR INDIFERENTE DA DATA DE HOJE E NÃO FOR VAZIA
            //echo $baixa_fs;
            //echo $data_hoje2;
            if($baixa_fs != $data_hoje2 and $baixa_fs != ''){
                $font_color = "blue";

            }else{
                //SE O STATUS FOR IGUAL A RETRABALHO E A BAIXA FOR NULA
                if($this->array_produto['PPIStatus'] == 'RETRABALHO' and $baixa_fs == NULL ){
                    $background = "red";
                    $font_color = "white";
                }

                //SE A DATA DE HOJE É IGUAL A BAIXA
                if($baixa_fs == $data_hoje2 ){
                    $background = "blue";
                    $font_color = "white";
                }
                
                //SE A DATA DO APONTAMENTO +10 HORAS NÃO FOR NULLA
                elseif(!empty($dtFinalHr)){
                    //SE O APONTAMENO FOR MENOR OU IGUAL O APONTAMENTO +10 E O APONTAMENTO NAO FOR NULO E O APONTAMENTO FOR HOJE
                    if($apontamento <= $dtFinalHr and $apontamento != NULL and $apontamento2 == $data_hoje2){
                        $background = "yellow";
                        $font_color = "black";
                    }

                    //SE O APONTAMENTO FOR DIFERENTE DE HOJE E O APONAMENTO NAO FOR NULO E FOR INDIFERENTE DE RETRABALHO
                    if($apontamento2 !=  $data_hoje2 and $apontamento != NULL and $this->array_produto['PPIStatus'] != 'RETRABALHO' ){
                        $background = "darkgray";
                        $font_color = "black";
                    }
                }
            }
                //-------------------------------TABELA INICIO--------------------------     



                
                
                echo    "<tr id='teste' style=color:$font_color;background:$background;>";

                echo        "<td>0</td>";

                echo        "<td class=op>". $this->array_produto['GORDE'] ."</td>";//OK

                
                //echo  "<td class=proc style=display:INHERIT>".$this->array_produto['GPROC'] ."</td>";
                echo  "<td class=proc style=display:none>".$this->array_produto['GPROC'] . "</td>";

                /*$value =  $this->array_produto['GORDE'];
                $_SESSION["newsession"] = $value;*/

                
                echo        "<td style=width:200px class=setor>". $this->array_produto['GMAQU'] . "</td>";//OK
                
                echo        "<td style=width:200px>". $this->array_produto['PREAC_Recurso'] ."</td>"; //OK               
                echo        "<td style=width:200px>". $this->array_produto['GPROG'] ."</td>";//OK
                echo        "<td style=width:200px>". $apontamento ."</td>";  //OK              
                
                echo        "<td>". $this->array_produto['PPIStatus'] ."</td>";    //OK           
                
                if($this->array_produto['PCPDATA'] == NULL or $this->array_produto['PCPDATA_Fim'] == NULL){
                echo        "<td></td>"; 
                }else{
                echo        "<td style=width:200px>". $this->array_produto['PCPDATA']->format('d-m') . '</br>'. $this->array_produto['PCPDATA_Fim']->format('d-m') .     "</td>";            

                }

                //NECESSARIO ACRESCENTAR DO BANCO
                echo        "<td>0</td>";
                echo        "<td style=width:200px>". $baixa_fs ."</td>";    
                echo        "<td style=width:200px>". $previa_fs ."</td>";            
                echo    "</tr>";
                //-----------------------------------FIM TABELA--------------------------------------------
        
        }
    
    }

    public function listar_operacao($operacao){
        while($this->array_produto = sqlsrv_fetch_array($this->query_produto)){ 
            echo $this->array_produto['GORDE'];
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
                echo                "<input class='form-control form_tabela' id='cliente_produto' value='". $array_cliente['CLIENTE']   ."' readonly style='width: 600px;'>";
                echo                "<input class='form-control form_tabela'  name=os_input id=os_input  type=hidden value='". $array_cliente['FPEDI']   ."' readonly>";
                echo            "</div>";
                echo         "<div class='col'>";               
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
                ECHO            "<input class='form-control form_tabela' name=pcp_obs id=pcp_obs type=hidden value='". $array_cliente['PCP_OBS']   ."' readonly>";
                echo        "</div>";
                echo    "</div>";
                echo    "</div>";
            }
        }


    }




?>