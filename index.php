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
    <div class="box">
        <form onsubmit="enviarLogin()">
            <div class="inputContainer">
                <input type="text" id="txt_email" required />
                <label>Email</label>
            </div>

            <div class="inputContainer">
                <input type="text" id="txt_senha" required />
                <label>Senha</label>
            </div>

            <button type="button" class="btn" onclick="enviarLogin()">Fazer Login</button>
        </form>
    </div>
</body>

<script>
    $('form').submit(() => {
        return false
    })

    function enviarLogin() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'src/login.php',
            data: {
                'email': document.getElementById('txt_email').value,
                'senha': document.getElementById('txt_senha').value
            },
            success: function(retorno) {
                if (retorno.codigo === 1) {
                    window.location.reload()
                    alert('Login efetuado com sucesso!')
                } else if (retorno.codigo === 2) {
                    alert(retorno.mensagem)
                } else if (retorno.codigo === 3) {
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