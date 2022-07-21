<?php
require('config/conexao.php');

//Requerimento do PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

if(isset($_GET['cod']) && !empty($_GET['cod'])) {
    //Limpar GET
    $cod = limparPost($_GET['cod']);

    //Verificar se a postagem existe de acordo com os campos
    if(isset($_POST['senha']) && isset($_POST['repete_senha'])) {
        //Verificar se todos os campos foram preenchidos
        if(empty($_POST['senha']) OR empty($_POST['repete_senha'])) {
            $erro_geral = "Todos os campos são obrigatórios!!";
        } else {
            //Receber valores vindo do post e limpar
            $senha = limparPost($_POST['senha']);
            $senha_cript = sha1($senha);
            $repete_senha = limparPost($_POST['repete_senha']);

            //Verificar se senha tem mais de 6 dígitos
            if(strlen($senha) < 6) {
                $erro_senha = "Senha deve ter pelo menos 6 caracteres";
            }

            //Verificar se repete senha é igual a senha
            if($senha !== $repete_senha) {
                $erro_repete_senha = "Senha e repetição de senha diferentes";
            }

            //Verificando se não há nenhum erro
            if(!isset($erro_geral) && !isset($erro_senha) && !isset($erro_repete_senha)) {
                //Verificar se esta recuperação de senha existe
                $sql = $pdo->prepare("SELECT * FROM usuarios WHERE recupera_senha = ? LIMIT 1");
                $sql->execute(array($cod));
                $usuario = $sql->fetch();

                //Se não existir
                if(!$usuario) {
                    echo "Recuperação de senha inválido";
                } else {
                    //Se existir
                    $sql = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE recupera_senha = ?");
                    if($sql->execute(array($senha_cript, $cod))) {
                        //Redirecionar para Login
                        header('location: index.php');
                    }
                }

            }
        }
    }
} else {
    header('location: index.php');
}

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
    <title>Trocar senha</title>
</head>
<body>
<form method="post">
    <h1>Trocar senha</h1>

    <?php if(isset($erro_geral)) { ?>
        <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo "$erro_geral"; ?>
        </div>
    <?php } ?>

    <div class="input-group">
        <img class="input-icon" src="img/lock.png" alt="">
        <input type="password" name="senha" <?php if(isset($erro_senha)){ echo 'class="error-input"';} ?> placeholder="Nova senha de pelo menos 6 dígitos" <?php if(isset($_POST['senha'])){ echo 'value="'.$_POST['senha'].'"';} ?> required>
        <?php if(isset($erro_senha)) { ?>
            <div class="erro"><?php echo $erro_senha; ?></div>
        <?php } ?>
    </div>

    <div class="input-group">
        <img class="input-icon" src="img/lock_open.png" alt="">
        <input type="password" name="repete_senha" <?php if(isset($erro_repete_senha)){ echo 'class="error-input"';} ?> placeholder="Repita a nova senha criada" <?php if(isset($_POST['repete_senha'])){ echo 'value="'.$_POST['repete_senha'].'"';} ?> required>
        <?php if(isset($erro_repete_senha)) { ?>
            <div class="erro"><?php echo $erro_repete_senha; ?></div>
        <?php } ?>
    </div>


    <button class="btn-blue" type="submit">Alterar a senha</button>
</form>
</body>
</html>