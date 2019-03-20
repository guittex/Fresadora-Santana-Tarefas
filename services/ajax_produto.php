<?php

    $proc = $_POST['proc'];
    $setor = $_POST['setor'];
    $op = $_POST['op'];

    echo    "<div class='col-2'>";
    echo        "<p class='label_tabela'>Operação:</p>";
    echo        "<input class='form-control form_tabela' value='". $op ."' readonly>";
    echo    "</div>";
    echo    "<div class='col-2'>";
    echo        "<p class='label_tabela'>Máquina:</p>";
    echo        "<input class='form-control form_tabela' value='". $setor ."' readonly>";
    echo    "</div>";
    echo    "<div  class='col-8'>";
    echo        "<p class='label_tabela'>Descrição:</p>";
    echo        "<input class='form-control form_tabela w-100' value='". $proc ."' readonly>";
    echo    "</div>";

    //print_r("cheguei ate o post do produto");

?>