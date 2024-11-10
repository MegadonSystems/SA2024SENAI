<?php

include_once '../class/BancoDeDados.php';

$formulario['nome'] = isset($_POST['nome'])? $_POST['nome'] : '';
$formulario['email'] = isset($_POST['email'])? $_POST['email'] : '';
$formulario['cpf'] = isset($_POST['cpf'])? $_POST['cpf'] : '';
$formulario['data_nascimento'] = isset($_POST['data_nascimento'])? $_POST['data_nascimento'] : '';

if(in_array('', $formulario)){
    echo json_encode(['codigo' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}

try{
    $banco = new BancoDeDados;

    $sql = 'INSERT INTO colaboradores (nome, email, cpf, data_nascimento) VALUES (?, ?, ?, ?)';
    $parametros = [$formulario['nome'], $formulario['email'], $formulario['cpf'], $formulario['data_nascimento']];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['codigo' => 1, 'mensagem' => 'Calaborador cadastrado com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}