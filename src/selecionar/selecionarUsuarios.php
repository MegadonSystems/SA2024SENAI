<?php
include_once '../class/BancoDeDados.php';

try {
    //Utilização do include_once pois já existe um include no clientes, que é a tela que inicia
    $banco = new BancoDeDados;
    $sql = "SELECT * FROM usuarios";

    // AQUI É DIFERENTE: queremos vários valores (todos) então é FETCHALL (true)
    $dados = $banco->consultar($sql, [], true);

    if($dados){
    // Verificação se houver dados dentro de $dados, imprime um script JS
    // para passar os valores no formulário

    $resposta = [
        'status' => 'sucesso',
        'dados' => $dados
    ];
    echo json_encode($resposta);
    }
    else{
        $resposta = [
            'status' => 'erro',
            'mensagem' => 'Nenhum usuário cadastrado!'
        ];
    echo json_encode($resposta); 
    }

} catch (PDOException $erro) {
    $resposta = [
        'status' => 'erro',
        'mensagem' => "Houve uma excessão no banco de dados: " + $erro->getMessage()
    ];
    echo json_encode($resposta);
}