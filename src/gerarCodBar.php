<?php

// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
if (empty($id)) {
    echo json_encode(['codigo' => 2, 'mensagem' => 'ID não encontrado!']);
    exit;
}

$api_url = "https://www.barcodesinc.com/generator/image.php?code={$id}&style=197&type=C128B&width=300&height=100&xres=1&font=3";


$nome_imagem = $id . '.jpg';
$destino = 'upload/codBar/' . $nome_imagem;

// Baixar a img
$imagem_baixada = @file_get_contents($api_url);

// Salvar img
file_put_contents($destino, $imagem_baixada);


echo json_encode(['codigo' => 1, 'Código de Barras gerado com sucesso!']);
