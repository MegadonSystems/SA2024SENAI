<?php
include_once 'class/BancoDeDados.php';

// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
if (empty($id)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID não encontrado!']);
    exit;
}

try{
    $banco = new BancoDeDados;

    $codigo = uniqid();

    $sql = 'UPDATE epis SET codigo_barra = ? WHERE id_epi = ?';
    $parametros = [$codigo, $id];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Código de Barras gerado com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
}
