<?php

include_once '../class/BancoDeDados.php';

// ID
$formulario['nome'] = isset($_POST['nome'])? $_POST['nome'] : '';
$formulario['descricao'] = isset($_POST['descricao'])? $_POST['descricao'] : '';
$formulario['estoque'] = isset($_POST['estoque'])? $_POST['estoque'] : '';
$formulario['imagem_new'] = isset($_FILES['imagem_new'])? $_FILES['imagem_new'] : '';

if(in_array('', $formulario)){
    echo json_encode(['codigo' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}


    // Enviar imagem para o servidor
    if (isset($_FILES['file_imagem']) && $_FILES['file_imagem']['size'] > 0) {
        $nome_img = uniqid() . '.jpg';
        move_uploaded_file($_FILES['file_imagem']['tmp_name'], 'upload/' . $nome_img);
    } else {
        $formulario['imagem_old'] = isset($_POST['imagem_old']) ? $_POST['imagem_old'] : '';

        if (empty($formulario['imagem'])) {
            echo json_encode(['codigo' => 1, 'mensagem' => 'Existem campo vázios. Verifique! 2']);
            exit;
        }else{
            $nome_img = $formulario['imagem'];
        }
    }


try{
    $banco = new BancoDeDados;

    if($formulario['id'] === 'NOVO'){
        $sql = 'INSERT INTO epis ...'
    }else{

    }

    
    echo json_encode(['codigo' => 1, 'mensagem' => 'Epi cadastrada com sucesso!']);
}catch(PDOException $erro){
    echo json_encode(['codigo' => 3, 'mensagem' => $erro->getMessage()]);
}