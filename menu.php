<?php


?>

<header class="cabecalho">
        <a href="http://192.168.1.214:8086/lista_tarefas/listar_tarefas/"><img src="public/img/logo_fresadora.jpg"></a>           

        <nav class="menu">

            <ul>
            
                <?php                  
                    if(isset($_SESSION["Nome_tarefa"])) {
                        $logado = $_SESSION["Nome_tarefa"];
                        ?>
                            <<?php echo "Bem vindo $logado";  ?></li>                           
                            <div class="dropdown">
								<i class="fa fa-user"></i><span style="margin-left:10px;">Bem vindo <?php echo $logado ?> <i class="fas fa-angle-down"></i></span>
								<div class="dropdown-content">
                                    <a href="index.php">Tarefas</a> <br>
									<a href="services/logoff.php" name="sair">Sair</a>                                                                   
								</div>                                
							</div>
                        <?php
                    }else{ ?>
                            <button class="btn-close">x</button>                           
                        <?php
                    }
                ?>
            <ul>
        </nav>
</header>