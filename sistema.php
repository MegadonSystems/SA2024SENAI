<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('location: index.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS -->
    <link rel="stylesheet" href="style/sistema.css">
    <link rel="stylesheet" href="style/emprestimo.css">
    <link rel="stylesheet" href="style/modal.css">

    <!-- Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Jquery -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title>Empréstimo de EPI's</title>

</head>

<body>
    <nav>
        <div id="decor">
            <img id="logo" src="assets/img/Labore-Consuloria-EPI-Placa.png" alt="">
            <p style="margin-bottom: 0; margin-left: 4%; font-size: 1.65rem">Empréstimo de EPI's</p>
        </div>
        <div id="item">
            <span class="divider"></span>
            <a id="epi" href="sistema.php?tela=epi" class="link"> <i class="bi bi-plus-circle-fill"></i></i> EPI</a>
            <span class="divider"></span>
            <a id="emprestimo" href="sistema.php?tela=emprestimo" class="link"> <i class="bi bi-handbag-fill"></i></i> Empréstimo </a>
            <span class="divider"></span>
            <a id="usuario" href="sistema.php?tela=usuario" class="link"> <i class="bi bi-person-fill-add"></i> Usuário </a>
            <span class="divider"></span>
            <a id="colaborador" href="sistema.php?tela=colaborador" class="link"> <i class="bi bi-person-fill-add"></i> Colaborador </a>
            <span class="divider"></span>
            <a href="#" class="link" onclick="sair()"><i class="bi bi-box-arrow-right"></i> SAIR</a>
        </div>
    </nav>
    <div id="main">
        <?php
        $tela = isset($_GET['tela']) ? $_GET['tela'] : '';

        switch ($tela) {

            case 'epi':
                include 'pages/epi.php';
                break;

            case 'colaborador':
                include 'pages/colaborador.php';
                break;

            case 'usuario':
                include 'pages/usuario.php';
                break;

            case 'emprestimo':
                include 'pages/emprestimo.php';
                break;

            default:
                include 'pages/emprestimo.php';

                break;
        }

        ?>

    </div>
</body>

<script>
    $('form').submit(() => {return false})


    function salvar() {

        const location = new URLSearchParams(window.location.search)
        const tela = location.get('tela')
        
        if (tela === "usuario") {
            $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'src/cadastrar/cadastrarUsuario.php',
            data: {
                'id': document.getElementById('txt_id').value,
                'nome': document.getElementById('nome').value,
                'email': document.getElementById('email').value,
                'senha': document.getElementById('senha').value
            },
            success: function(retorno) {
                if(retorno.status === 'sucesso'){
                    alert(retorno.mensagem)
                    fecharModal()
                    listarUsuario()
                }else{
                    alert(retorno.mensagem)
                }
            },
            error: function(erro) {
                alert('Ocorreu um problema técnico, entre em contato com o suporte')
                console.log(erro)
            }
            });

        } else if (tela === "colaborador") {
            $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'src/cadastrar/cadastrarColaborador.php',
            data: { 
                'id': document.getElementById('txt_id').value,
                'nome': document.getElementById('nome').value,
                'email':  document.getElementById('email').value,
                'cpf': document.getElementById('cpf').value,
                'data_nascimento': document.getElementById('data_nascimento').value,
            },
            success: function(retorno) {
                if(retorno.status === 'sucesso'){
                    alert(retorno.mensagem)
                    fecharModal()
                    listarColaborador()
                }else{
                    alert(retorno.mensagem)
                }
            },
            error: function(erro) {
                alert('Ocorreu um problema técnico, entre em contato com o suporte')
                console.log(erro)
            }
            });
        } else if (tela === "epi"){
            const dados = new FormData()
            dados.append('id', document.getElementById('txt_id').value)
            dados.append('nome', document.getElementById('nome').value)
            dados.append('descricao', document.getElementById('descricao').value)
            dados.append('estoque', document.getElementById('estoque').value)

            dados.append('imagem_new', document.getElementById('imagem').files[0])
            dados.append('imagem_old', document.getElementById('txt_imagem').value)
            
            $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            dataType: 'JSON',
            url: 'src/cadastrar/cadastrarEpi.php',
            data: dados,
            success: function(retorno) {
                if(retorno.status === 'sucesso'){
                    alert(retorno.mensagem)
                    fecharModal()
                    listarEpi()
                }else{
                    alert(retorno.mensagem)
                }
            },
            error: function(erro) {
                alert('Ocorreu um problema técnico, entre em contato com o suporte')
                console.log(erro)
            }
            });

        }
        else if (tela === "emprestimo"){
            const dataAtual = new Date().toLocaleDateString().split('/').reverse().join('-')
            
            $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'src/cadastrar/cadastrarEmprestimo.php',
            data: { 
                'descricao':  document.getElementById('descricao').value,
                'quantidade':  document.getElementById('quantidade').value,
                'data_retirada': dataAtual,
                'id_colaborador': document.getElementById('fk_colaborador').value,
                'id_epi': document.getElementById('fk_epi').value
            },
            success: function(retorno) {
                if(retorno.status === 'sucesso'){
                    alert(retorno.mensagem)
                    fecharModal()
                    listarEmprestimo()
                }else{
                    alert(retorno.mensagem)
                }
            },
            error: function(erro) {
                alert('Ocorreu um problema técnico, entre em contato com o suporte')
                console.log(erro)
            }
            });
    }
}

    function sair() {
        var confirmou = confirm('Tem certeza que deseja sair do sistema?')
        if (confirmou) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'src/logout.php',
                success: function(retorno) {
                    window.location = 'index.php'
                },
                error: function(retrno){
                    alert('Ocorreu um problema, abra um chamado!')
                }
            })
        }
    }

    function abrirModal() {
        $('#adicionar').modal('show')
    }

    function fecharModal(){
        $('#adicionar').modal('hide')
    }


    document.addEventListener('DOMContentLoaded', () => {
        const url = new URLSearchParams(window.location.search)
        const tela = url.get('tela')
        document.getElementById(tela).classList.add('bold')
    })

    
</script>

</html>