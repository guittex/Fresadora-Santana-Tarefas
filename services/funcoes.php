<?php


class funcoes{


    public function HrDecToString($horasdecimal){
    // Define o formato de saida
    $formato = '%02d:%02d';
    // Converte para minutos

    $horasInteiras = abs($horasdecimal);

    $minutos = $horasInteiras * 60;

    // Converte para o formato hora
    $horas = floor($minutos / 60);
    $minutos = ($minutos % 60);
    
    if($horasdecimal < 0 ){
        $horas = $horas * -1;
    }
    // Retorna o valor
    return sprintf($formato, $horas, $minutos);
    
    }

}

?>