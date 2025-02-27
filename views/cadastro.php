<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../public/login/src/style/cadastro.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=visibility" />
</head>
<body>
    <main>
        <div class="main">
            <div class="header_main">
                <h1>Cadastre-se a <span class="rainbow">Paradox Arena!</span></h1>
            </div>
            <div class="form">
                <form action="/paradoxarena/public/registrar" method="POST">
                    <div class="flexbox">
                        <div class="ld1">
                            <div class="inputs">
                                <label for="nome_completo">NOME COMPLETO</label>
                                <input type="text" name="nome_completo" placeholder="ANDRE MARQUES" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">CPF (somente numeros)</label>
                                <input type="number" name="cpf" placeholder="CPF (somente números)" required pattern="[0-9]{11}" maxlength="11">
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">NICKNAME</label>
                                <input type="text" name="nickname" placeholder="pimbolado" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">E-MAIL</label>
                                <input type="text" name="email" placeholder="exemplo@gmail.com" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">GÊNERO</label><br>
                                <select name="genero" id="genero" required>
                                    <option value="">SELECIONE</option>
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FÊMENINO</option>
                                </select>
                            </div>
                        </div>
                        <div class="ld2">
                            <div class="inputs" id="password">   
                                <label for="nome_completo">SENHA</label>
                                <input type="password" name="senha" placeholder="EXEMPLO#123" required>
                                <span class="material-symbols-outlined" id="show" translate="no">visibility</span>
                            </div>
                            <div class="inputs" id="password">   
                                <label for="nome_completo">CONFIRMAR SENHA</label>
                                <input type="password" name="confirmar_senha" placeholder="EXEMPLO#123" required>
                                <span class="material-symbols-outlined" id="show" translate="no">visibility</span>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">CHAVE PIX (PARA SAQUES)</label>
                                <input type="text" name="chave" placeholder="exemplo@gmail.com" required>
                            </div>
                            <div class="inputs">
                                <label for="nome_completo">DATA DE NASCIMENTO <label>
                                <input type="date" name="data_nascimento" name="" required>
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
                        <button type="submit">CADASTRAR</button>
                        <p>JA TEM UMA CONTA? <a href="login.php">ENTRAR</a></p>
                    </div>
                </form>
            </div>
      
        </div>
    </main>
</body>
</html>