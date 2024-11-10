<?php

include_once '../class/BancoDeDados.php';

$formulario['descricao'] = isset($_POST['descricao'])? $_POST['descricao'] : '';
$formulario['data_retirada'] = isset($_POST['data_retirada'])? $_POST['data_retirada'] : '';
$formulario['id_epi'] = isset($_POST['id_epi']) ? $_POST['id_epi'] : '';
$formulario['id_colaborador'] = isset($_POST['id_colaborador']) ? $_POST['id_colaborador'] : '';

//Validação dos campos obrigatorios
if(in_array('', $formulario)){
    echo json_encode(['codigo' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}

$formulario['data_devolucao'] = isset($_POST['data_devolucao'])? $_POST['data_devolucao'] : '';

try{
    $banco = new BancoDeDados;

    $sql = 'INSERT INTO emprestimos (status, descricao, data_retirada, data_devolucao, id_epi, id_colaborador) VALUES (?, ?, ?, ?, ?, ?)';
    $parametros = ['1', $formulario['descricao'], $formulario['data_retirada'], $formulario['data_devolucao'], $formulario['id_epi'], $formulario['id_colaborador']];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['codigo' => 1, 'mensagem' => 'Emprestimo cadastrado com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}
