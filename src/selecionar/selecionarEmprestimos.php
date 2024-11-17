<?php
include_once '../class/BancoDeDados.php';

try {
    $banco = new BancoDeDados;
    $sql = "SELECT emprestimos.*, 
                colaboradores.nome AS colaborador, 
                epis.nome AS epi 
            FROM emprestimos
                    INNER JOIN colaboradores USING(id_colaborador)
                    INNER JOIN epis USING(id_epi)
            ORDER BY emprestimos.status ASC, emprestimos.data_retirada DESC
    ";

    // AQUI É DIFERENTE: queremos vários valores (todos) então é FETCHALL (true)
    $dados = $banco->consultar($sql, [], true);

    if($dados){
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