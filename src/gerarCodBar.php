<?php
require_once 'class/BancoDeDados.php';

// IMPORTANTE !!!!!!!!!
// Caso requisitar essa arquivo utilizar metodo GET
// pois o metodo POST está sendo usado para gerar automatico

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sql = "SELECT id_epi FROM epis ORDER BY id_epi DESC";

    $id = $banco->consultar($sql);
    $id = $id['id_epi'];
}else{
    $banco = new BancoDeDados;

    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if (empty($id)) {
        echo json_encode(['codigo' => 2, 'mensagem' => 'ID não encontrado!']);
        exit;
    }
}

try{
    $api_url = "https://www.barcodesinc.com/generator/image.php?code={$id}&style=197&type=C128B&width=300&height=100&xres=1&font=3";


    $nome_imagem = $id . '.jpg';

    // Alterar o caminho deacordo onde está sendo usado o arquivo
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $destino = '../upload/codBar/' . $nome_imagem;
    }else{
        $destino = 'upload/codBar/' . $nome_imagem;
    }

    // Baixar a img
    $imagem_baixada = @file_get_contents($api_url);

    // Salvar img
    file_put_contents($destino, $imagem_baixada);

    $sql = 'UPDATE epis SET codigo_barra = ? WHERE id_epi = ?';
    $parametros = [$nome_imagem, $id];

    $banco->executarComando($sql, $parametros);

    // Só executa essa linha quando for requisitar essa arquivo
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        echo json_encode(['codigo' => 1, 'mensagem' => 'Código de Barras gerado com sucesso!']);
    }
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}
