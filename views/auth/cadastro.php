<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../public/css/auth/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body>
    <main>
        <div class="main">
            <div class="header_main">
                <h1>Cadastre-se a <span class="rainbow">Paradox Arena!</span></h1>
            </div>
            <div class="form">
                <form action="/paradoxarena/public/registrar" id="myForm" method="POST">
                <?php
                // No início da view, inicie a sessão se ainda não estiver iniciada
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Exibe as mensagens de erro, se houver
                if (isset($_SESSION['erros']) && !empty($_SESSION['erros'])) {
                    echo '<div class="alert alert-danger">';
                    foreach ($_SESSION['erros'] as $erro) {
                        echo '<p>' . htmlspecialchars($erro) . '</p>';
                    }
                    echo '</div>';
                    // Limpa as mensagens de erro após exibição
                    unset($_SESSION['erros']);
                }

                // Exibe mensagem de sucesso, se houver
                if (isset($_SESSION['sucesso'])) {
                    echo '<div class="alert alert-success">';
                    echo '<p>' . htmlspecialchars($_SESSION['sucesso']) . '</p>';
                    echo '</div>';
                    unset($_SESSION['sucesso']);
                }
                ?>
                    <div class="flexbox">
                        <div class="ld1">
                            <div class="inputs">
                                <label for="nome_completo">NOME COMPLETO</label>
                                <input type="text" name="nome_completo" placeholder="ANDRE MARQUES" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">CPF (somente numeros)</label>
                                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00">
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">NICKNAME</label>
                                <input type="text" name="nickname" placeholder="pimbolado" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">GÊNERO</label><br>
                                <select name="genero" class="select" required>
                                    <option value="">SELECIONE</option>
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FÊMENINO</option>
                                </select>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">E-MAIL</label>
                                <input type="email" name="email" placeholder="exemplo@gmail.com" required>
                            </div>
                        </div>

                        <div class="ld2">
                            <div class="inputs" id="password">   
                                <label for="nome_completo">SENHA</label>
                                <input type="password" name="senha" placeholder="EXEMPLO#123" required>
                                <span class="material-symbols-outlined toggle-password" id="show" translate="no">visibility</span>
                            </div>
                            <div class="inputs" id="password">   
                                <label for="nome_completo">CONFIRMAR SENHA</label>
                                <input type="password" name="confirmar_senha" placeholder="EXEMPLO#123" required>
                                <span class="material-symbols-outlined toggle-password" id="show" translate="no">visibility</span>
                            </div>

                            <div class="inputs">
                                <label for="nome_completo">DATA DE NASCIMENTO <label>
                                <input type="date" name="data_nascimento" name="" required>
                            </div>

                            
                            <div class="inputs">
                                <label for="pixType">Tipo de chave PIX (PARA SAQUES)</label>
                                    <select id="pixType" name="tipodechave" class="select" name="pixType" required>
                                        <option value="cpf">CPF</option>
                                        <option value="cnpj">CNPJ</option>
                                        <option value="email">Email</option>
                                        <option value="telefone">Telefone</option>
                                        <option value="aleatoria">Aleatória</option>
                                    </select>
                            </div>

                            <div class="inputs">
                                <label for="pixKey">Chave PIX (PARA SAQUES)</label>
                                <input type="text" id="pixKey" name="chave" placeholder="Digite a chave PIX" required>
                            </div>
                            
                        </div>
                    </div>
                    <div class="checkbox">
                        <div class="termos">
                            <input type="checkbox" name="termos" id="termos" required>
                            <label for="termos">Eu li e aceito <a href="">os termos</a><label>
                        </div>
                    </div>
                    
                    <div class="button">
                        <button type="submit" id="submitBtn">CADASTRAR</button>
                        <p>JA TEM UMA CONTA? <a href="login">ENTRAR</a></p>
                    </div>
                </form>
            </div>
      
        </div>
    </main>
</body>
</html>
<script src="/paradoxarena/public/script/auth/formatocpf.js"></script>