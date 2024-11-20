<?php
include_once '../class/BancoDeDados.php';

$formulario['descricao'] = isset($_POST['descricao'])? $_POST['descricao'] : '';
$formulario['quantidade'] = isset($_POST['quantidade'])? $_POST['quantidade'] : '';
$formulario['data_retirada'] = isset($_POST['data_retirada'])? $_POST['data_retirada'] : '';
$formulario['id_epi'] = isset($_POST['id_epi']) ? $_POST['id_epi'] : '';
$formulario['id_colaborador'] = isset($_POST['id_colaborador']) ? $_POST['id_colaborador'] : '';

//Validação dos campos obrigatorios
if(in_array('', $formulario)){
    echo json_encode(['status' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}


try{
    $banco = new BancoDeDados;

    //Validando a quantidade de estoque da epi
    $sql = 'SELECT qtd_estoque FRom epis WHERE id_epi = ?';
    $parametros = [$formulario['id_epi']];
    $qtdEpi = $banco->consultar($sql, $parametros);
    $qtdEpi = $qtdEpi['qtd_estoque'];

    $prevQtdEpi = $qtdEpi - $formulario['quantidade'];

    if($qtdEpi < 0 || $prevQtdEpi < 0){
        echo json_encode(['status' => 'erro', 'mensagem' => 'Não há estoque suficiente para realizar esse emprestimo']);
        exit;
    }


    $banco->iniciarTransacao();

    $sql = 'INSERT INTO emprestimos (descricao, quantidade, data_retirada, id_epi, id_colaborador) VALUES (?, ?, ?, ?, ?)';
    $parametros = [$formulario['descricao'], $formulario['quantidade'], $formulario['data_retirada'], $formulario['id_epi'], $formulario['id_colaborador']];
    $banco->executarComando($sql, $parametros);


    $sql = 'UPDATE epis set qtd_estoque = qtd_estoque - ? WHERE id_epi = ?';
    $parametros = [$formulario['quantidade'], $formulario['id_epi']];
    $banco->executarComando($sql, $parametros);

    $banco->confirmarTransacao();
    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Emprestimo cadastrado com sucesso!']);
}catch(PDOException $erro){
    $banco->voltarTransacao();
    echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
}
