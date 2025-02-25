<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="src/style/cadastro.css">
</head>
<body>

    <section>

    <div class="main">
        <div class="flex">
            <div class="ld1">
                <img src="src/img/logogrande.webp" alt="" width="400px">
                <div class="txt">
                    <h1>Seja bem vindo!</h1>
                </div>
            </div>
            <div class="ld2">
                <form action="../../controllers/auth/cadastrar_usuario.php" method="POST">
                    <h3>Dados Pessoais</h3>

                    <div class="dados">
                        <label for="nome_completo">Nome Completo</label>
                        <input type="text" name="nome_completo" placeholder="Nome Completo" required>
                        
                        <label for="E-mail">Email:</label>
                        <input type="email" name="email" placeholder="E-mail" required>
                        
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" placeholder="Senha" required>
                        
                        <label for="senha">Confirmar Senha:</label>
                        <input type="password" name="confirmar_senha" placeholder="Senha" required> 
                        
                        <label for="nickname">Nick nos jogos</label>    
                        <input type="text" name="nickname" placeholder="Nickname" required>
                        
                        <label for="cpf">CPF:</label>
                        <input type="text" name="cpf" placeholder="CPF (somente números)" required pattern="[0-9]{11}" maxlength="11">
                        
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" placeholder="10/10/2000" required>
                        
                        <label>Gênero:</label>
                        <input type="radio" id="feminino" name="genero" value="F" required>
                        <label for="feminino">Feminino</label>
                        
                        <input type="radio" id="masculino" name="genero" value="M" required>
                        <label for="masculino">Masculino</label>
                    </div>

                    <div class="dados_pix">
                        <h3>Chave de Pagamento</h3>
                        <input type="text" name="chave" placeholder="Digite sua Chave Pix" required>
                        <select name="tipode_de_chave" required>
                            <option value="cpf">CPF</option>
                            <option value="email">E-mail</option>
                            <option value="telefone">Telefone</option>
                            <option value="aleatoria">Chave Aleatória</option>
                        </select>
                        <button type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </section>
</body>
</html>