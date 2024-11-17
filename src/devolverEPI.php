<?php
require_once 'class/BancoDeDados.php';

$id = isset($_POST['id'])? $_POST['id'] : '';

if(empty($id)){
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido ou não existe! Verifique']);
}

try{
    $banco = new BancoDeDados;
    $banco->iniciarTransacao();

    $parametros = [$id];

    $sql = 'UPDATE emprestimos SET status = 2 WHERE id_emprestimo = ?';
    $banco->executarComando($sql, $parametros);

    $sql = 'SELECT id_epi, quantidade FROM emprestimos WHERE id_emprestimo = ?';
    $dados = $banco->consultar($sql, $parametros);

    // Colocar a data de devolução
    $sql = 'UPDATE emprestimos SET data_devolucao = NOW() WHERE id_emprestimo = ?';
    $parametros = [$id];
    $banco->executarComando($sql, $parametros);

    $sql = 'UPDATE epis SET qtd_estoque = qtd_estoque + ? WHERE id_epi = ?';
    $parametros = [$dados['quantidade'], $dados['id_epi']];
    $banco->executarComando($sql, $parametros);



    $banco->confirmarTransacao();
    echo json_encode(['status' => 'sucesso', 'mensagem' => 'EPI devolvido com sucesso!']);

}catch(PDOException $erro){
    $banco->voltarTransacao();
    echo json_encode(['status' => 'erro', 'mensagem' => $erro->getMessage()]);
}