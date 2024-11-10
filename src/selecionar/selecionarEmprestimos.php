<?php
include_once '../class/BancoDeDados.php';

try {
    //Utilização do include_once pois já existe um include no clientes, que é a tela que inicia
    $banco = new BancoDeDados;
    $sql = "SELECT * FROM emprestimos";

    // AQUI É DIFERENTE: queremos vários valores (todos) então é FETCHALL (true)
    $dados = $banco->consultar($sql, [], true);

    if($dados){
    //Trocar o ID pelo Nome ↓

    // Colaborador
    for($i = 0; $i < count($dados); $i++){
        $sql = 'SELECT nome FROM colaboradores WHERE id_colaborador = ?';
        $parametros = [$dados[$i]['id_colaborador']];

        $colab = $banco->consultar($sql, $parametros, false);

        $dados[$i]['id_colaborador'] = $colab['nome'];
    }

    // EPI
    for($i = 0; $i < count($dados); $i++){
        $sql = 'SELECT nome FROM epis WHERE id_epi = ?';
        $parametros = [$dados[$i]['id_epi']];

        $epi = $banco->consultar($sql, $parametros, false);

        $dados[$i]['id_epi'] = $epi['nome'];
    }

    $resposta = [
        'status' => 'sucesso',
        'dados' => $dados
    ];
    echo json_encode($resposta);
    }
    else{
        $resposta = [
            'status' => 'erro',
            'mensagem' => 'Nenhum empréstimo cadastrado!'
        ];
    echo json_encode($resposta); 
    }

} catch (PDOException $erro) {
    $resposta = [
        'status' => 'erro',
        'mensagem' => "Houve uma excessão no banco de dados: " . $erro->getMessage()
    ];
    echo json_encode($resposta);
}