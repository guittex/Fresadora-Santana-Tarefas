<?php

$horasInteiras = 8.9911;
// Define o formato de saida
$formato = '%02d:%02d';
// Converte para minutos
$minutos = $horasInteiras * 60;

// Converte para o formato hora
$horas = floor($minutos / 60);
$minutos = ($minutos % 60);

echo sprintf($formato, $horas, $minutos)

//echo ('Resto= ' .$resto);
//echo '<br>';
//echo ('Minuto= ' .$minutos);
//echo '<br>';
//echo ('Hora Final= ' .$horas.':'.$minutos);



?>