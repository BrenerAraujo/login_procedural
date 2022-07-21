<?php
//Iniciando sessão
session_start();

//Dois modos possíveis. local e producao
$modo = 'local';

if($modo == 'local') {
    $servidor = 'localhost';
    $usuario = 'root';
    $senha = '';
    $banco = 'dimitri_login';
}

if($modo == 'producao') {
    $servidor = '';
    $usuario = '';
    $senha = '';
    $banco = '';
}

try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Banco conectado com sucesso!!';
} catch(PDOException $erro){
    echo "Falha ao se conectar com o banco";
}

function limparPost($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

function auth($tokenSessao) {
    global $pdo;

    //Verificar se tem autorização
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token = ? LIMIT 1");
    $sql->execute(array($tokenSessao));

    $usuario = $sql->fetch((PDO::FETCH_ASSOC));
//Se não encontrar usuário
    if(!$usuario) {
        return false;
    } else {
        return $usuario;
    }
}