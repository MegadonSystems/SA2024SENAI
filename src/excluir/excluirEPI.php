<?php
require_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';
if(empty($id)){
    echo json_encode(['status' => 2, 'mensagem' => 'Não foi possivel obter o id!']);
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


    echo json_encode(['status' => 1, 'mensagem' => 'EPI removido com sucesso!']);

}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}