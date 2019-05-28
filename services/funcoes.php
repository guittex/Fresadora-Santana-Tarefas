<?php


class funcoes{


    public function HrDecToString($horasdecimal){
        // Define o formato de saida
        $formato = '%02d:%02d';
        // Converte para minutos

        $negativo = "";

        $horasInteiras = abs($horasdecimal);

        $minutos = $horasInteiras * 60;

        // Converte para o formato hora
        $horas = floor($minutos / 60);
        $minutos = ($minutos % 60);
        

        if($horasdecimal < 0 ){
            $negativo = "-";
        }
        
        // Retorna o valor
        if($horas <= 24){
            return $negativo . sprintf($formato, $horas, $minutos);

        }elseif($horasdecimal < 0){
            $dias = ($horas / 9);
            $dias =  round($dias, 0);
            return $negativo . $dias . " dias";

        }else{
            $dias = ($horas / 9);
            $dias =  round($dias, 0);
            return $dias . " dias";
        }
        
    }

}

?>