<?php
require('config/conexao.php');

//Verificação de autenticação
$user = auth($_SESSION['TOKEN']);
if($user) {
    echo "<h1>Esta é a página restrita 2</h1>";
    echo "<br><br><a style='background: green; color: white; text-decoration: none; padding: 20px; border-radius: 5px;' href='logout.php'>Sair do Sistema</a>";
} else {
    //Redirecionar para o login
    header('location: index.php');
}
?>