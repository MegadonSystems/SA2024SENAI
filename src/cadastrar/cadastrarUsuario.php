<?php

include_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';
$nome = isset($_POST['nome'])? $_POST['nome'] : '';
$email = isset($_POST['email'])? $_POST['email'] : '';
$senha = isset($_POST['senha'])? $_POST['senha'] : '';


if(empty($nome) || empty($email) || empty($senha)){
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos! Verifique']);
    exit;
}

try{
    $banco = new BancoDeDados;

    if($id === 'NOVO'){
        $sql = 'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)';
        $parametros = [$nome, $email, $senha];

        $banco->executarComando($sql, $parametros);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Usuário cadastrado com sucesso!']);
    }else{
        $sql = 'UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id_usuario = ?';
        $parametros = [$nome, $email, $senha, $id];

        $banco->executarComando($sql, $parametros);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Usuário atualizado com sucesso!']);
    }

}catch(PDOException $erro){
    echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
}