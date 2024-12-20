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

    <!-- Boostrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <img src="assets/img/11zon_cropped (1).jpeg" id="background" alt="">
    <div class="box">
        <form onsubmit="enviarLogin()">
            <div class="orgLogin">
                <h1>Entrar no sistema</h1>
            </div>

            <div class="inputContainer">
                <input type="email" id="txt_email" required />
                <label>Email</label>
            </div>

            <div class="inputContainer">
                <input type="password" id="txt_senha" required />
                <label>Senha</label>
            </div>

            <button type="button" class="btn" onclick="enviarLogin()">Login</button>

            <div class="orgLogin" id="eSenha">
                <h3>
                    <a href="" onclick="alertLogin()">Esqueceu a senha?</a>
                </h3>
            </div>
        </form>
    </div>

    <footer>
        <img style="padding: 0; margin: 0;" src="assets/img/senai-logo-1.png" alt="" id="senai">
        <h6 style="padding: 0; margin: 0; padding-top:4px">Eduardo Luís Maltauro & João Vitor Rosset - 2024 <a style="color: #0d6efd; text-decoration: underline" href="https:/github.com/MegadonSystems/SA2024SENAI"><i class="bi bi-link-45deg"></i>(MGD Sistemas)</a></h3>
    </footer>
</body>

<script>
    $('form').submit(() => {
        return false
    })

    function alertLogin() {
        alert('Contate o suporte para redefinir sua senha!')
    }

    function enviarLogin() {
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'src/login.php',
            data: {
                'email': document.getElementById('txt_email').value,
                'senha': document.getElementById('txt_senha').value
            },
            success: function(retorno) {
                if (retorno.status === 'sucesso') {
                    window.location.reload()
                    alert('Login efetuado com sucesso!')
                } else {
                    alert(retorno.mensagem)
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