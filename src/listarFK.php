<?php
include_once 'class/BancoDeDados.php';

try{
$banco = new BancoDeDados;
$sql1 = "SELECT id_colaborador, nome FROM colaboradores";
$sql2 = "SELECT id_epi, nome FROM epis";

$dadosC = $banco->consultar($sql1, [], true);
$dadosE = $banco->consultar($sql2, [], true);

$resposta = [
    'status' => 'sucesso',
    'dadosC' => $dadosC,
    'dadosE' => $dadosE
];

echo json_encode($resposta);

} catch (PDOException $erro) {
    $resposta = [
        'status' => 'erro',
        'mensagem' => "Houve uma excessão no banco de dados: " + $erro->getMessage()
    ];
    echo json_encode($resposta);
}