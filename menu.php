<?php

?>

<header class="cabecalho">

        <div class="row">
            <div class="col-6">
                <a href="http://192.168.1.214:8086/lista_tarefas/listar_tarefas/"><img src="public/img/logo_fresadora.jpg"></a>           
            </div>  
            <div class="col-6">
            <?php if(isset($_SESSION['login'])) { ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style='float: right;'>
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav" style="background: #e6e1e1;border: 1px solid #e6e1e1; border-radius: 15px;box-shadow: 1px 2px 15px;">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Bem vindo <?php echo $_SESSION['login'] ?> <span class="sr-only">(Página atual)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="services/logoff.php">Sair</a>
                        </li>                    
                    </ul>
                </div>
            </nav>
            <?php } ?>
            </div>
        </div>

</header>