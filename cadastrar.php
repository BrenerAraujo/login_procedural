<?php
    require('config/conexao.php');


?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Cadastrar</title>
</head>
<body>
    <form>
        <h1>Cadastrar</h1>

        <div class="erro-geral animate__animated animate__rubberBand">
            Aqui vai o erro para o usuário
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/card.png" alt="">
            <input type="text" placeholder="Nome completo">
            <div class="erro">Por favor informe um nome válido!</div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png" alt="">
            <input type="email" placeholder="Seu melhor email">
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png" alt="">
            <input type="password" placeholder="Senha de pelo menos 6 dígitos">
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock_open.png" alt="">
            <input type="password" placeholder="Repita a senha criada">
        </div>

        <div class="input-group">
            <input type="checkbox" id="termos" name="termos" value="ok">
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de Privacidade</a> e
                <a class="link" href="#">Termos de uso</a>.</label>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>
</html>