<?php
include_once 'class/BancoDeDados.php';

// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
if (empty($id)) {
    echo json_encode(['codigo' => 2, 'mensagem' => 'ID não encontrado!']);
    exit;
}

try{
    $banco = new BancoDeDados;


    $api_url = "https://www.barcodesinc.com/generator/image.php?code={$id}&style=197&type=C128B&width=300&height=100&xres=1&font=3";


    $nome_imagem = $id . '.jpg';
    $destino = 'upload/codBar/' . $nome_imagem;

    // Baixar a img
    $imagem_baixada = @file_get_contents($api_url);

    // Salvar img
    file_put_contents($destino, $imagem_baixada);

    $sql = 'UPDATE epis SET codigo_barra = ? WHERE id_epi = ?';
    $parametros = [$nome_imagem, $id];

    $banco->executarComando($sql, $parametros);

    echo json_encode(['codigo' => 1, 'mensagem' => 'Código de Barras gerado com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}
