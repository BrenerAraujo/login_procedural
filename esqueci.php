<?php
require('config/conexao.php');

//Requerimento do PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

if(isset($_POST['email']) && !empty($_POST['email'])) {
    //Receber os dados vindos do POST e limpar
    $email = limparPost($_POST['email']);
    $status = "confirmado";

    //Verificar se existe usuário
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND status = ? LIMIT 1 ");
    $sql->execute(array($email, $status));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if($usuario) {
        //Existe usuário
        //Enviar email para o usuário criar nova senha
        $mail = new PHPMailer(true);
        $cod = sha1(uniqid());

        //Atualizar o código de recuperação deste usuário no banco
        $sql = $pdo->prepare("UPDATE usuarios SET recupera_senha = ? WHERE email = ?");
        if($sql->execute(array($cod, $email))) {
            try {
                //Recipients
                $mail->setFrom('login@sistemalogin.com', 'Sistema de Login');         //Quem está mandando o email
                $mail->addAddress($email, $nome);                               //Quem recebe o email

                //Content
                $mail->isHTML(true);                                  //Corpo do email como HTML
                $mail->Subject = 'Recuperar senha!';            //Título do email
                $mail->Body    = '<h1>Recuperar a senha:</h1><a style="background: green; color: white; text-decoration: none; padding: 20px; border-radius: 5px;" href="https://seusistema.com.br/recuperar-senha.php?codigo='.$cod.'">Recuperar a senha</a><br><br><p>Equipe de Login</p></p>';

                $mail->send();
                header('location: recupera-senha.html');

            } catch (Exception $e) {
                echo "Houve um problema ao enviar o e-mail de confirmação: {$mail->ErrorInfo}";
            }
        }

    } else {
        $erro_usuario = "Houve uma falha ao buscar este e-mail. Teste novamente";
    }
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
    <title>Esqueceu a senha</title>
</head>
<body>
    <form method="post">
        <h1>Recuperar senha</h1>

        <?php if(isset($erro_usuario)) { ?>
            <div style="text-align: center;" class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_usuario; ?>
            </div>
        <?php } ?>

        <p>Informe o e-mail cadastrado no sistema</p>
        <div class="input-group">
            <img class="input-icon" src="img/user.png" alt="">
            <input type="email" name="email" placeholder="Digite seu e-mail">
        </div>

        <button class="btn-blue" type="submit">Recuperar a senha</button>
        <a href="cadastrar.php">Voltar para login</a>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>