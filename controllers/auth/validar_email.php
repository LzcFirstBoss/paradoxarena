<?php
// ativar_conta.php
require_once '../../config/public/database.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $pdo = (new Database())->connect();
    
    // Procura o token na tabela usuario_tokens
    $stmt = $pdo->prepare("SELECT * FROM usuario_tokens WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $tokenData = $stmt->fetch();
    
    if ($tokenData) {
        // Verifica se o token não expirou
        if ($tokenData['expira_em'] < date('Y-m-d H:i:s')) {
            echo "Token expirado. Por favor, solicite um novo link de ativação.";
        } else {
            // Atualiza a tabela usuario para marcar o e-mail como verificado
            $stmt = $pdo->prepare("UPDATE usuario SET email_verificado = 1 WHERE id = :id");
            $stmt->execute(['id' => $tokenData['user_id']]);
            
            // Remove o token, pois já foi utilizado
            $stmt = $pdo->prepare("DELETE FROM usuario_tokens WHERE id = :id");
            $stmt->execute(['id' => $tokenData['id']]);
            
            echo "Seu e-mail foi confirmado com sucesso! Agora você pode acessar sua conta.";
        }
    } else {
        echo "Token inválido.";
    }
} else {
    echo "Token não fornecido.";
}
?>
