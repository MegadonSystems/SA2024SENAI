<?php
include_once '../class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';


if(empty($id)){
    $resposta = [
        'status' => 2,
        'mensagem' => 'Não foi possível obter o id!'
    ];
    echo json_encode($resposta);

    exit;
}


try {
    //Utilização do include_once pois já existe um include no clientes, que é a tela que inicia
    $banco = new BancoDeDados;
    $sql = "SELECT * FROM colaboradores WHERE id_colaborador = ?";
    $parametros = [$id];

    // AQUI É DIFERENTE: queremos vários valores (todos) então é FETCHALL (true)
    $dados = $banco->consultar($sql, $parametros, false);

    if($dados){
    // Verificação se houver dados dentro de $dados, imprime um script JS
    // para passar os valores no formulário

    $resposta = [
        'status' => 1,
        'dados' => $dados
    ];
    echo json_encode($resposta);
    }
    else{
        $resposta = [
            'status' => 2,
            'mensagem' => 'Nenhum colaborador cadastrado!'
        ];
    echo json_encode($resposta); 
    }

} catch (PDOException $erro) {
    $resposta = [
        'status' => 3,
        'mensagem' => "Houve uma excessão no banco de dados: " + $erro->getMessage()
    ];
    echo json_encode($resposta);
}