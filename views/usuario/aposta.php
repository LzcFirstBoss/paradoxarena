<?php
include_once __DIR__ . '/../../helpers/protectuser.php';
requireAuth();
$activePage = 'apostados';

// Inicia o buffer de saída
ob_start();
?>

<!-- inicio html -->

<div class="apostar">
    <button>criar aposta</button>
</div>

<!--fim html -->

<?php
// Captura todo o conteúdo gerado e armazena na variável $content
$content = ob_get_clean();

// Agora, inclui o template principal, que usará a variável $content dentro da div desejada
include_once __DIR__ . '/../templates/main.php';
?>
