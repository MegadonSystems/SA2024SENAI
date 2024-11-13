<?php
require_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';
if(empty($id)){
    echo json_encode(['status' => 2, 'mensagem' => 'Não foi possivel obter o id!']);
    exit;
}

try{
    $banco = new BancoDeDados();

    $sql = 'DELETE FROM colaboradores WHERE id_colaborador = ?';
    $parametros = [$id];

    $banco->executarComando($sql, $parametros);


    echo json_encode(['status' => 1, 'mensagem' => 'Colaborador removido com sucesso!']);

}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}