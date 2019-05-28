<?php

class conexao{

    public $servidor;
    public $usuario;
    public $pass;
    public $conninfo;
    public $con;
    public $database;

    public function sql_conexao(){

        //$database = strtoupper($database);
        //Banco padrao
        $this->servidor = "ENTERPRISE\ENTERPRISE";
        $this->usuario = "sa";
        $this->pass = "atusfresadora";
        $this->database = "Cpd";       
    
        $this->conninfo = array("Database" => $this->database,  "UID" => $this->usuario, "PWD" => $this->pass, "CharacterSet" => "UTF-8");
    
        $this->con = sqlsrv_connect($this->servidor, $this->conninfo);
        
        if (!$this->con) {
        
            die(print_r(sqlsrv_errors(), true));

        }
    }

        public function getConexao(){
            return var_dump($this->con);
        }    
    

}










?>