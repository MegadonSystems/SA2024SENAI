<?php
include_once 'class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';

if(empty($id)){
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido ou não existe! Verifique']);
}

try{
    $banco = new BancoDeDados;

    $sql = 'UPDATE emprestimos SET statusE = 2 where id_emprestimo = ?';
    $parametros = [$id];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'EPI devolvido com sucesso!']);



}catch(PDOException $erro){
    echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
}