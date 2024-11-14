<?php
include_once '../class/BancoDeDados.php';


$formulario['id'] = isset($_POST['id'])? $_POST['id'] : '';
$formulario['nome'] = isset($_POST['nome'])? $_POST['nome'] : '';
$formulario['descricao'] = isset($_POST['descricao'])? $_POST['descricao'] : '';
$formulario['estoque'] = isset($_POST['estoque'])? $_POST['estoque'] : '';

//Validação dos campos obrigatorios
if(in_array('', $formulario)){
    echo json_encode(['status' => 2, 'mensagem' => 'Existem campos vazios! Verifique']);
    exit;
}

$formulario['imagem_new'] = isset($_FILES['imagem_new'])? $_FILES['imagem_new'] : '';
$formulario['imagem_old'] = isset($_POST['imagem_old'])? $_POST['imagem_old'] : '';



try{
    $banco = new BancoDeDados;

    if($formulario['id'] === 'NOVO'){

        //Validar se a imagem chegou
        if($formulario['imagem_new']['size'] <= 0){
            echo json_encode(['status' => 2, 'mensagem' => 'Existem campo vazios. Verifique!']);
            exit;
        }

        $nome_img = uniqid() . '.jpg';
        move_uploaded_file($formulario['imagem_new']['tmp_name'], '../upload/' . $nome_img);
        $formulario['imagem'] = $nome_img;



        $sql = 'INSERT INTO epis (nome, descricao, qtd_estoque, imagem) VALUES (?, ?, ?, ?)';
        $parametros = [$formulario['nome'], $formulario['descricao'], $formulario['estoque'], $formulario['imagem']];

        $banco->executarComando($sql, $parametros);

        echo json_encode(['status' => 1, 'mensagem' => 'EPI cadastrado com sucesso!']);
    }else{

        if (isset($formulario['imagem_new']) && !empty($formulario['imagem_old'])) {
            //Validar se a imagem chegou
            if($formulario['imagem_new']['size'] <= 0){
                echo json_encode(['status' => 2, 'mensagem' => 'Existem campo vazios. Verifique!']);
                exit;
            }

            $sql = 'SELECT imagem FROM epis WHERE id_epi = ?';
            $parametros = [$formulario['id']];

            $img = $banco->consultar($sql, $parametros);
            $img = $img['imagem'];


            if(file('../upload/'.$img)){
                unlink('../upload/'.$img);
            }

            $nome_img = uniqid() . '.jpg';
            move_uploaded_file($formulario['imagem_new']['tmp_name'], '../upload/' . $nome_img);
            $formulario['imagem'] = $nome_img;

            
        } else {

            if (empty($formulario['imagem_old'])) {
                echo json_encode(['status' => 2, 'mensagem' => 'Existem campo vazios. Verifique!']);
                exit;
            }else{
                $formulario['imagem'] = $formulario['imagem_old'];
            }
        }


        $sql = 'UPDATE epis SET nome = ?, descricao = ?, qtd_estoque = ?, imagem = ? WHERE id_epi = ?';
        $parametros = [$formulario['nome'], $formulario['descricao'], $formulario['estoque'], $formulario['imagem'], $formulario['id']];

        $banco->executarComando($sql, $parametros);

        echo json_encode(['status' => 1, 'mensagem' => 'EPI atualizado com sucesso!']);
    }
}catch(PDOException $erro){
    echo json_encode(['status' => 3, 'mensagem' => $erro->getMessage()]);
}


