<?php

$email = isset($_POST['email']) ?   $_POST['email'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($email) || empty($senha)) {
    echo json_encode(['codigo' => 2, 'mensagem' => 'Por favor preencha todos os campos!']);
    exit;
}

try {
    include 'class/BancoDeDados.php';
    $banco = new BancoDeDados;

    $sql = "SELECT id_usuario, nome FROM usuarios WHERE email = ? AND senha = ?";

    $param = [ $email, $senha ];

    $dados = $banco->consultar($sql, $param);

    if ($dados) {
        session_start();
        $_SESSION['logado'] = true;
        $_SESSION['id'] = $dados['id_usuario'];
        $_SESSION['nome'] = $dados['nome'];

        echo json_encode(['codigo' => 1]);
    } else {
        echo json_encode(['codigo' => 2, 'mensagem' => 'Dados incorretos! Por favor, verifique']);
    }
} catch (PDOException $erro) {
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}
