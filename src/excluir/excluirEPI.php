<?php
require_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';
if(empty($id)){
    echo json_encode(['status' => 'erro', 'mensagem' => 'Não foi possivel obter o id!']);
    exit;
}

try{
    $banco = new BancoDeDados();
    $parametros = [$id];


    $sql = 'SELECT imagem FROM epis WHERE id_epi = ?';
    $img = $banco->consultar($sql, $parametros);
    $img = $img['imagem'];
    if(file('../upload/'.$img)){
        unlink('../upload/'.$img);
    }

    $sql = 'DELETE FROM epis WHERE id_epi = ?';
    $parametros = [$id];
    $banco->executarComando($sql, $parametros);


    echo json_encode(['status' => 'sucesso', 'mensagem' => 'EPI removido com sucesso!']);

}catch(PDOException $erro){
    if($erro->getCode() == 23000) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Não é possível excluir esse colaborador, pois ele está vinculado ao um emprestimo']);
    }else{
        echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
    }
}