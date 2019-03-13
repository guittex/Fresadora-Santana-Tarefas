<?php


class funcoes{


    public function HrDecToString($horasInteiras){
    // Define o formato de saida
    $formato = '%02d:%02d';
    // Converte para minutos
    $minutos = $horasInteiras * 60;

    // Converte para o formato hora
    $horas = floor($minutos / 60);
    $minutos = ($minutos % 60);

    // Retorna o valor
    return sprintf($formato, $horas, $minutos);
    }

}

?>