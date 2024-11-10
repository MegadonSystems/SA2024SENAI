<?php
require_once '../class/BancoDeDados.php';


$formulario['id'] = isset($_POST['id'])? $_POST['id'] : '';
$formulario['nome'] = isset($_POST['nome'])? $_POST['nome'] : '';
$formulario['descricao'] = isset($_POST['descricao'])? $_POST['descricao'] : '';
$formulario['estoque'] = isset($_POST['estoque'])? $_POST['estoque'] : '';

//Validação dos campos obrigatorios
if(in_array('', $formulario)){
    echo json_encode(['codigo' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}

$formulario['imagem_new'] = isset($_FILES['imagem_new'])? $_FILES['imagem_new'] : '';
$formulario['imagem_old'] = isset($_POST['imagem_old'])? $_POST['imagem_old'] : '';


//Enviando a imagem pro servido
if (isset($formulario['imagem_new']) && $formulario['imagem_new']['size'] > 0) {
    $nome_img = uniqid() . '.jpg';
    move_uploaded_file($formulario['imagem_new']['tmp_name'], '../upload/imgEpis/' . $nome_img);
    $formulario['imagem'] = $nome_img;
} else {
    $formulario['imagem_old'] = isset($_POST['imagem_old']) ? $_POST['imagem_old'] : '';

    if (empty($formulario['imagem_old'])) {
        echo json_encode(['codigo' => 2, 'mensagem' => 'Existem campo vázios. Verifique!']);
        exit;
    }else{
        $formulario['imagem'] = $formulario['imagem_old'];
    }
}


try{
    $banco = new BancoDeDados;

    if($formulario['id'] === 'NOVO'){
        $sql = 'INSERT INTO epis (nome, descricao, qtd_estoque, imagem) VALUES (?, ?, ?, ?)';
        $parametros = [$formulario['nome'], $formulario['descricao'], $formulario['estoque'], $formulario['imagem']];

        $banco->executarComando($sql, $parametros);

        echo json_encode(['codigo' => 1, 'mensagem' => 'Epi cadastrada com sucesso!']);

        // Gerar Código de Barras
        include_once '../gerarCodBar.php';
    }else{
        // Desenvolver o alterar
    }
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}