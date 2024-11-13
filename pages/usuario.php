<div id="view">
  <div id="top2">
    <button onclick="abrirModal()" id="btn"> <i class="bi bi-plus-lg"></i> Cadastrar Usuário</button>
  </div>

  <table class="main-table" id="tabela-usuarios" style="border-bottom: 1px solid black; border-right: 1px solid black">
    <thead>
      <tr style="background-color: rgb(44, 44, 44);; color: antiquewhite;">
        <th style="width:50px">#</th>
        <th>Nome</th>
        <th>Email</th>
        <th style="width: 250px;">Senha</th>
        <th style="width: 160px; border-bottom: none">Ações</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<div id="adicionar" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form_produto" onsubmit="salvar()">
        <div class="modal-header">
          <h4 id="titulo" class="modal-title">Cadastro de Usuário</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="txt_id" value="NOVO">
          <input type="hidden" id="txt_nome_imagem">

          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" id="nome" required maxlength="255">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="email" required maxlength="255">
          </div>
          <div class="form-group">
            <label>Senha</label>
            <input type="password" class="form-control" id="senha" required maxlength="255">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.onload = function() {
    listarUsuario();
  }


  function listarUsuario() {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/selecionar/selecionarUsuarios.php',
      success: function(retorno) {
        console.log(retorno)
        if (retorno['status'] == 'sucesso') {
          var tabelaUsuarios = document.querySelector('#tabela-usuarios tbody');
          tabelaUsuarios.innerHTML = '';
          var usuarios = retorno['dados'];
          usuarios.forEach(function(usuario) {
            var linha = document.createElement('tr');
            linha.innerHTML = 
            `
              <td style="width:50px">${usuario['id_usuario']}</td>
              <td id="nomeFont">${usuario['nome']}</td>
              <td>${usuario['email']}</td>
              <td style="width: 250px; border-right: none">${usuario['senha']}</td>
              <td style="width: 160px; border-bottom: none; border-right: none" class='orgAcao'>  
              <a class='acao' href='#' title='Alterar' onclick='carregarUsuario(${usuario['id_usuario']})' style='margin-left:30px'><i class='bi bi-pencil-square'></i></i></i></a>
              <a class='acao' href='#' title='Excluir' onclick='excluirUsuario(${usuario['id_usuario']})' style='margin-left:30px'><i class='bi bi-trash3-fill'></i></a>
              </td>
            `

            tabelaUsuarios.appendChild(linha)
          })

        } else {
          var linha = document.createElement('tr')
          linha.innerHTML = `<tr> <td colspan='8'> Nenhum usuário cadastrado! </td> </tr>`
          document.querySelector('#tabela-usuarios tbody').appendChild(linha)
        }

      },
      error: function(erro) {
        console.error('Ocorreu um erro na requisição: ' + erro);
      }
    });
  }

    function carregarUsuario(idUsuario){
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/carregar/carregarUsuario.php',
        data: {
            'id': idUsuario
        },
        success: function(retorno) {
            if (retorno['status'] == 1) {
                // Imprimir dados do usuário no modal

                document.getElementById('txt_id').value = retorno['dados']['id_usuario']
                document.getElementById('nome').value = retorno['dados']['nome']
                document.getElementById('email').value = retorno['dados']['email']
                document.getElementById('senha').value = retorno['dados']['senha']
                
                document.getElementById('titulo').innerHTML = 'Cadastro do Usuário'

                abrirModal()

            } else {
                alert(retorno['mensagem'])
            }
        },
        error: function(erro) {
            alert('Ocorreu um erro na requisição: ' + erro);
        }
    })
    }

    function excluirUsuario(idUsuario) {
    //Confirma se o usuário quer excluir ou n
    let confirmar = confirm("Deseja realmente excluir esse usuário?");

    if (confirmar) {
        //Requisição assincrona ajax
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'src/excluir/excluirUsuario.php',
            data: {
                'id': idUsuario
            },
            success: function(retorno) {
                alert(retorno['mensagem'])

                if (retorno['status'] == 1) {

                    listarUsuario(); //Atualizar a listagem de usuários
                    // window.location.reload()
                }
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        })
    }
}  
</script>