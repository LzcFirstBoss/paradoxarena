<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body>
    <div class="main">
        <div class="img">
            <img src="/../../paradoxarena/public/img/404.svg" alt="">
        </div>
        <div class="link">
            <span>Página não encontrada,</span>
           <a href="./">Voltar para o inicio.</a>
        </div>
    </div>
</body>
</html>

<style>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

    :root{
--color-background: #1f1f1f;
--cor-paragrafo: #333;
--cor-paradox: #7ed957;
}

body{
    background-color: var(--color-background);
}

.main{
    text-align: center;
    margin-top: 5%
}

.img img{
    width: 30%;
}

.link{
    color: white;
    font-size: 30px;
    margin-top: 20px;
    font-family: "Montserrat", serif;

}

.link a{
    color: white;
}

.link a:hover{
    color: red;
}

</style>