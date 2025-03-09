<?php
include_once __DIR__ . '/../../helpers/protectuser.php';
requireAuth();

// Inicia o buffer de saída
ob_start();
?>

<!-- inicio html -->
<h1>Bem-vindo ao Dashboard</h1>
<p>Esta é uma mensagem dinâmica inserida na área principal.</p>
<p style="color: white;"><?php print_r($_SESSION); ?></p>
<div style="display: flex; color: red;">
    <div>
        <h1>b</h1>
    </div>
    <div>
        <h1>a</h1>
    </div>
</div>
<!--fim html -->

<?php
// Captura todo o conteúdo gerado e armazena na variável $content
$content = ob_get_clean();

// Agora, inclui o template principal, que usará a variável $content dentro da div desejada
include_once __DIR__ . '/../templates/main.php';
?>
