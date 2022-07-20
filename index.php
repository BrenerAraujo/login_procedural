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
    <title>Login</title>
</head>
<body>
    <form>
        <h1>Login</h1>

        <?php if(isset($_GET['result']) && $_GET['result'] == 'ok') { ?>
            <div class="sucesso animate__animated animate__rubberBand">
                Usuário cadastrado com sucesso!!
            </div>
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/user.png" alt="">
            <input type="email" placeholder="Digite seu nome completo">
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png" alt="">
            <input type="password" placeholder="Digite sua senha">
        </div>

        <button class="btn-blue" type="submit">Fazer Login</button>
        <a href="cadastrar.php">Ainda não tenho cadastro</a>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php if(isset($_GET['result']) && $_GET['result'] == 'ok') { ?>
    <script>
        setTimeout(() => {
            $('.sucesso').hide();
        }, 3000);
    </script>
    <?php } ?>
</body>
</html>