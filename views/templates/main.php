<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/templates/templatemain.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <?php if (isset($customCSS)): ?>
        <link rel="stylesheet" href="<?php echo $customCSS; ?>">
    <?php endif; ?>
</head>
<body>

    <div class="mainmenu">
        <div class="menu">
            <div class="usuario">
                <div class="img_usuario">
                    <img src="/paradoxarena/public/img/teste.jpg" alt="">
                </div>
                <div class="inf_usuario">
                    <h2><?php echo htmlspecialchars($userData['nome_completo']); ?></h2>
                    <h3><?php echo htmlspecialchars($userData['email']); ?></h3>
                </div>
            </div>
    
            <div class="nav">
                <h4>Paginas</h4>
                <div class="links">
                    <a href="/paradoxarena/public/dashboard" <?php echo (isset($activePage) && $activePage === 'dashboard') ? 'id="ativado"' : ''; ?>>
                        <span class="material-symbols-outlined" translate="no">home</span> Inicio
                    </a>
                    <a href="/paradoxarena/public/apostados" <?php echo (isset($activePage) && $activePage === 'apostados') ? 'id="ativado"' : ''; ?>>
                        <span class="material-symbols-outlined" translate="no">paid</span> Apostados
                    </a>
                    <a href="/paradoxarena/public/rank" <?php echo (isset($activePage) && $activePage === 'rank') ? 'id="ativado"' : ''; ?>>
                        <span class="material-symbols-outlined" translate="no">crown</span> Rank
                    </a>
                    <a href="/paradoxarena/public/carteira" <?php echo (isset($activePage) && $activePage === 'deposito') ? 'id="ativado"' : ''; ?>>
                    <span class="material-symbols-outlined">currency_exchange</span> Depositar
                    </a>
                    <a href="/paradoxarena/public/historico" <?php echo (isset($activePage) && $activePage === 'historico') ? 'id="ativado"' : ''; ?>>
                        <span class="material-symbols-outlined" translate="no">history</span> Historico
                    </a>
                </div>
            </div>
    
            <div class="footer_menu nav">
                <div class="links_footer">
                    <a href="/paradoxarena/public/logout" id="logout"><span class="material-symbols-outlined" translate="no">logout</span> Sair</a>
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
                        <h2><?php echo htmlspecialchars($userData['nome_completo']); ?></h2>
                        <h3><?php echo htmlspecialchars($userData['email']); ?></h3>
                    </div>
                </div>
                <div class="icon">
                    <p class="rainbow paradox"><span class="material-symbols-outlined" translate="no" id="iconswords">swords</span> Paradox Arena</p>
                    <a href="/paradoxarena/public/carteira" id="carteira">
                    <div class="carteira">
                        <i class="bi bi-coin" id="iconcarteira"></i>
                       <p id="saldo">R$<?php echo number_format($walletData['saldo'] ?? 0, 2, ',', '.'); ?></p>
                    </div>
                    </a>
                    <a href="" id="configs"><span class="material-symbols-outlined" translate="no">settings</span></a>
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
