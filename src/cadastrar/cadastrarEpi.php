<?php

include_once '../class/BancoDeDados.php';

// CAMPOS e IFs
// ...

try{
    $banco = new BancoDeDados;

    // SQL e Parametros

    
    echo json_encode(['codigo' => 1, 'mensagem' => 'Epi cadastrada com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}