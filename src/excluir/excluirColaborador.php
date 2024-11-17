<?php
require_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';
if(empty($id)){
    echo json_encode(['status' => 'erro', 'mensagem' => 'Não foi possivel obter o id!']);
    exit;
}

try{
    $banco = new BancoDeDados();

    $sql = 'DELETE FROM colaboradores WHERE id_colaborador = ?';
    $parametros = [$id];

    $banco->executarComando($sql, $parametros);


    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Colaborador removido com sucesso!']);

}catch(PDOException $erro){
    if($erro->getCode() == 23000) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Não é possível excluir esse colaborador, pois ele está vinculado ao um emprestimo']);
    }else{
        echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
    }
}