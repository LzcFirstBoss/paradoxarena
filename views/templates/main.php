<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/templates/templatemain.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body>

    <div class="mainmenu">
        <div class="menu">
            <div class="usuario">
                <div class="img_usuario">
                    <img src="/paradoxarena/public/img/teste.jpg" alt="">
                </div>
                <div class="inf_usuario">
                    <h2><?php echo htmlspecialchars($_SESSION['usuario']['nome_completo']);?></h2>
                    <h3><?php echo htmlspecialchars($_SESSION['usuario']['email']);?></h3>
                </div>
            </div>
    
            <div class="nav">
                <h4>Paginas</h4>
                <div class="links">
                    <a href="" id="ativado"><span class="material-symbols-outlined">home</span> Inicio</a>
                    <a href=""><span class="material-symbols-outlined">paid</span>Apostados</a>
                    <a href=""><span class="material-symbols-outlined">swords</span> Camps</a>
                    <a href=""><span class="material-symbols-outlined">crown</span> Rank</a>
                    <a href=""><span class="material-symbols-outlined">history</span> Historico</a>
                </div>
            </div>
    
            <div class="footer_menu nav">
                <div class="links_footer">
                    <a href="/paradoxarena/public/logout" id="logout"><span class="material-symbols-outlined">logout</span> Sair</a>
                </div>
            </div>
        </div>

        <div class="header_menu">
            <div class="mainheader">
                <div class="usuariomobile usermobile">
                    <div class="img_usuario">
                        <img src="/paradoxarena/public/img/teste.jpg" alt="">
                    </div>
                    <div class="inf_usuario">
                        <h2><?php echo htmlspecialchars($_SESSION['usuario']['nome_completo']);?></h2>
                        <h3><?php echo htmlspecialchars($_SESSION['usuario']['email']);?></h3>
                    </div>
                </div>
                <div class="icon">
                    <p class="rainbow paradox"><span class="material-symbols-outlined" id="iconswords">swords</span> Paradox Arena</p>
                    <div class="carteira">
                        <span class="material-symbols-outlined" id="iconcarteira">account_balance_wallet</span>
                        <p id="saldo">R$<?php echo number_format($walletBalance, 2, ',', '.'); ?></p>
                    </div>
                    <a href="" id="configs"><span class="material-symbols-outlined">settings</span></a>
                </div>
            </div>
            <div class="main">
            <?php
        // Exibe o conteúdo dinâmico se ele existir
        if (isset($content)) {
            echo $content;
        }
        ?>
            </div>
        </div>
        
    </div>

</body>
</html>
