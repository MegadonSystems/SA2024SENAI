<?php

include_once '../class/BancoDeDados.php';

$nome = isset($_POST['nome'])? $_POST['nome'] : '';
$email = isset($_POST['email'])? $_POST['email'] : '';
$senha = isset($_POST['senha'])? $_POST['senha'] : '';


if(empty($nome) || empty($email) || empty($senha)){
    echo json_encode(['codigo' => 2, 'mensagem' => 'Preencha todos os campos! Verifique']);
    exit;
}

try{
    $banco = new BancoDeDados;

    $sql = 'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)';
    $parametros = [$nome, $email, $senha];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['codigo' => 1, 'mensagem' => 'Usuário cadastrado com sucesso!']);

}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}