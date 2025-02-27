<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="src/style/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=visibility" />
</head>
<body>
    <main>
        <div class="main">
            <div class="header_main">
                <h1>Bem vindo a <span class="rainbow">Paradox Arena!</span></h1>
            </div>
            <div class="form">
                <form action="POST">
                    <div>
                        <input type="text" placeholder="CPF/EMAIL">
                    </div>
                    <div id="password">   
                        <input type="password" placeholder="SENHA">
                        <span class="material-symbols-outlined" id="show" translate="no">visibility</span>
                    </div>

                    <div class="checkbox">
                        <div class="lembrar">
                            <input type="checkbox" name="lembrar">
                            <label for="lembrar">Lembrar</label>
                        </div>
                        <div class="recuperar_senha">
                            <a href="">Esquci minha senha</a>
                        </div>
                    </div>

                    <div class="button">
                        <button type="submit">ENTRAR</button>
                        <p>AINDA NÃO TEM UMA CONTA? <a href="cadastro.php">CADASTRE-SE</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>