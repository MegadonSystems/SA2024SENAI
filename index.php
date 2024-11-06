<?php
session_start();
if (isset($_SESSION['logado'])) {
    header('location: sistema.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo de EPI's</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style/index.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="pai">
        <img id="imgEpi" src="assets/img/trabalhador-de-uniforme-com-capacete-e-oculos-de-protecao_23-2148773461.jpg" alt="">
        <div id="divLogin">
            <div id="orgLogin">
                <div id="texto">
                    <p id="titulo" style="margin-top: 5.5%;"> <b>Sistema de empréstimo de EPI's</b></p> <br> <br> <br>
                    <p id="subtitulo">https://devsnap.me/css-input-text</p>
                </div>
                <div id="formLogin">
                    <form id="formulario" onsubmit="enviarLogin()">
                        <input class="input" id="txt_email" type="text" placeholder="https://devsnap.me/css-input-text" required>
                        
                        <input class="input" id="txt_senha" type="password" placeholder="https://devsnap.me/css-input-text" required>
                        <button id="btnEnviar" name="btnEnviar">https://devsnap.me/css-input-text</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script> 

    $('form').submit(() => {return false})

    function enviarLogin(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'src/login.php',
            data: {
                'email': document.getElementById('txt_email').value,
                'senha': document.getElementById('txt_senha').value
            },
            success: function(retorno) {
                if(retorno.codigo === 1){
                    window.location.reload()
                    alert('Login efetuado com sucesso!')
                }else if(retorno.codigo === 2){
                    alert(retorno.mensagem)
                }else if(retorno.codigo === 3){
                    alert('Ocorreu um problema técnico, reinicie a página ou volte mais tarde, caso não resolver contate o suporte')
                    console.log(retorno.mensagem)
                }
            },
            error: function(erro) {
                alert('Ocorreu um problema técnico, entre em contato com o suporte')
                console.log(erro)
            }
            })
    }
</script>

</html>