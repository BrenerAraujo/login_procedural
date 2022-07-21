<?php
require('config/conexao.php');

//Requerimento do PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

//Verificar se a postagem existe de acordo com os campos
if(isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])) {
    //Verificar se todos os campos foram preenchidos
    if(empty($_POST['nome_completo']) OR empty($_POST['email']) OR empty($_POST['senha']) OR empty($_POST['repete_senha']) OR empty($_POST['termos'])) {
        $erro_geral = "Todos os campos são obrigatórios!!";
    } else {
        //Receber valores vindo do post e limpar
        $nome = limparPost($_POST['nome_completo']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //Verificar se nome é apenas letras e espaços
        if(!preg_match("/^[a-zA-Z-' ]*$/", $nome)) {
            $erro_nome = "Somente permitido letras e espaços no nome";
        }

        //Verificar se email é válido
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_email = "Formato de e-mail inválido";
        }

        //Verificar se senha tem mais de 6 dígitos
        if(strlen($senha) < 6) {
            $erro_senha = "Senha deve ter pelo menos 6 caracteres";
        }

        //Verificar se repete senha é igual a senha
        if($senha !== $repete_senha) {
            $erro_repete_senha = "Senha e repetição de senha diferentes";
        }

        //Verificar se checkbox foi marcado
        if($checkbox !== "ok") {
            $erro_checkbox = "A marcação do campo é obrigatória";
        }

        //Verificando se não há nenhum erro
        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)) {
            //Verificar se o e-mail já está cadastrado
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
            $sql->execute(array($email));
            $usuario = $sql->fetch();

            //Se não existir o usuário, adicionar no banco
            if(!$usuario) {
                $recupera_senha = "";
                $token = "";
                $codigo_confirmacao = uniqid();
                $modo == "local" ? $status = "confirmado" : $status = "novo";
                $data_cadastro = date('d/m/Y');

                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?)");

                if($sql->execute(array($nome, $email, $senha_cript, $recupera_senha, $token, $codigo_confirmacao, $status, $data_cadastro))) {
                    //Se for em modo local
                    if($modo == "local"){
                        header('location: index.php?result=ok');
                    }
                    //Se o modo for produção
                    if($modo == "producao") {
                        //Enviar email para o usuário
                        $mail = new PHPMailer(true);

                        try {
                            //Recipients
                            $mail->setFrom('from@example.com', 'Sistema de Login');         //Quem está mandando o email
                            $mail->addAddress($email, $nome);                               //Quem recebe o email

                            //Content
                            $mail->isHTML(true);                                  //Corpo do email como HTML
                            $mail->Subject = 'Confirme seu cadastro!';            //Título do email
                            $mail->Body    = '<h1>Por favor confirme seu e-mail abaixo</h1><a style="background: green; color: white; text-decoration: none; padding: 20px; border-radius: 5px;" href="https://seusistema.com.br/confirmacao.php?cod_confirm='.$codigo_confirmacao.'">Confirmar E-mail</a><br><br><p>Equipe de Login</p></p>';

                            $mail->send();
                            header('location: obrigado.html');

                        } catch (Exception $e) {
                            echo "Houve um problema ao enviar o e-mail de confirmação: {$mail->ErrorInfo}";
                        }
                    }
                }
            } else {
                //Já existe usuário. Apresentar erro
                $erro_geral = "Email já cadastrado";
            }

        }
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
    <title>Cadastrar</title>
</head>
<body>
    <form method="post">
        <h1>Cadastrar</h1>

        <?php if(isset($erro_geral)) { ?>
            <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo "$erro_geral"; ?>
            </div>
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/card.png" alt="">
            <input type="text" name="nome_completo" <?php if(isset($erro_nome)){ echo 'class="error-input"';} ?> placeholder="Nome completo" <?php if(isset($_POST['nome_completo'])){ echo 'value="'.$_POST['nome_completo'].'"';} ?> required>
            <?php if(isset($erro_nome)) { ?>
                <div class="erro"><?php echo $erro_nome; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png" alt="">
            <input type="email" name="email" <?php if(isset($erro_email)){ echo 'class="error-input"';} ?> placeholder="Seu melhor email" <?php if(isset($_POST['email'])){ echo 'value="'.$_POST['email'].'"';} ?> required>
            <?php if(isset($erro_email)) { ?>
                <div class="erro"><?php echo $erro_email; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png" alt="">
            <input type="password" name="senha" <?php if(isset($erro_senha)){ echo 'class="error-input"';} ?> placeholder="Senha de pelo menos 6 dígitos" <?php if(isset($_POST['senha'])){ echo 'value="'.$_POST['senha'].'"';} ?> required>
            <?php if(isset($erro_senha)) { ?>
                <div class="erro"><?php echo $erro_senha; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock_open.png" alt="">
            <input type="password" name="repete_senha" <?php if(isset($erro_repete_senha)){ echo 'class="error-input"';} ?> placeholder="Repita a senha criada" <?php if(isset($_POST['repete_senha'])){ echo 'value="'.$_POST['repete_senha'].'"';} ?> required>
            <?php if(isset($erro_repete_senha)) { ?>
                <div class="erro"><?php echo $erro_repete_senha; ?></div>
            <?php } ?>
        </div>

        <div class="input-group <?php if(isset($erro_checkbox)){ echo 'error-input';} ?>">
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de Privacidade</a> e
                <a class="link" href="#">Termos de uso</a>.</label>
            <?php if(isset($erro_checkbox)) { ?>
                <div class="erro"><?php echo $erro_checkbox; ?></div>
            <?php } ?>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>
</html>