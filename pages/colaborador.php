<div id="view">
  <div class="testeGITHUB" id="top2">
    <button onclick="abrirModal()" id="btn"> <i class="bi bi-plus-lg"></i> Cadastrar Colaborador</button>
  </div>

  <table class="main-table" id="tabela-colaboradores" style="border-right: 1px solid black; border-bottom: 1px solid black">
    <thead>
      <tr style="background-color: rgb(44, 44, 44);; color: antiquewhite;">
        <th>#</th>
        <th>Nome</th>
        <th>E-Mail</th>
        <th>CPF</th>
        <th>Data Nascimento</th>
        <th style="width: 160px; border-bottom: none">Ações</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>

  <div id="adicionar" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="form_produto" onsubmit="salvar()">
          <div class="modal-header">
            <h4 class="modal-title">Cadastro de Colaborador</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="txt_id" value="novo">
            <input type="hidden" id="txt_nome_imagem">

            <div class="form-group">
              <label>Nome</label>
              <input type="text" class="form-control" id="nome" required>
            </div>
            <div class="form-group">
              <label>E-Mail</label>
              <input type="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
              <label>CPF</label>
              <input type="text" class="form-control" id="cpf" required maxlength="14">
            </div>
            <div class="form-group">
              <label>Data Nascimento</label>
              <input type="date" class="form-control" id="data_nascimento" required>
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

    $(function (){
      $('#cpf').mask('000.000.000-00')      
    })

    window.onload = function() {
      listarColaborador();
    }

    function listarColaborador() {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/selecionar/selecionarColaboradores.php',
        success: function(retorno) {
          console.log(retorno)
          if (retorno['status'] == 'sucesso') {
            var tabelaColaboradores = document.querySelector('#tabela-colaboradores tbody');
            var colaboradores = retorno['dados'];
            colaboradores.forEach(function(colaborador) {
              var dataNascimento = new Date(colaborador['data_nascimento']);
              var dataFormatada = dataNascimento.toLocaleDateString('pt-BR');

              var linha = document.createElement('tr');
              linha.innerHTML =
                `
              <td style="width:50px">${colaborador['id_colaborador']}</td>
              <td id="nomeFont">${colaborador['nome']}</td>
              <td>${colaborador['email']}</td>
              <td style="width: 250px; border-right: none">${colaborador['cpf']}</td>
              <td style="width: 250px; border-right: none">${dataFormatada}</td>
              <td style="width: 160px; border-bottom: none; border-right: none" class='orgAcao'>  
              <a class='acao' href='#' onclick='carregarColaborador(${colaborador['id_colaborador']})' title='Alterar' style='margin-left:30px'><i class='bi bi-pencil-square'></i></i></i></a>
              <a class='acao' href='#' onclick='excluirColaborador(${colaborador['id_colaborador']})' title='Excluir' style='margin-left:30px'><i class='bi bi-trash3-fill'></i></a>
              </td>
            `

              tabelaColaboradores.appendChild(linha)
            })

          } else {
            var linha = document.createElement('tr')
            linha.innerHTML = `<tr> <td colspan='8'> Nenhum colaborador cadastrado! </td> </tr>`
            document.querySelector('#tabela-colaboradores tbody').appendChild(linha)
          }

        },
        error: function(erro) {
          console.error('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }

    function carregarColaborador(idColaborador){
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/carregar/carregarColaborador.php',
        data: {
            'id_colaborador': idColaborador
        },
        success: function(retorno) {
            if (retorno['status'] == 'sucesso') {
                // Imprimir dados do colaborador no modal

                document.getElementById('id').value = retorno['dados']['id_colaborador']
                document.getElementById('nome').value = retorno['dados']['nome']
                document.getElementById('cpf').value = retorno['dados']['cpf']
                document.getElementById('data_nascimento').value = retorno['dados']['data_nascimento']
                document.getElementById('email').value = retorno['dados']['email']

            } else {
                alert(retorno['mensagem'])
            }
        },
        error: function(erro) {
            alert('Ocorreu um erro na requisição: ' + erro);
        }
    })
    }

    function excluirColaborador(idColaborador) {
    //Confirma se o usuário quer excluir ou n
    let confirmar = confirm("Deseja realmente excluir esse colaborador?");

    if (confirmar) {
        //Requisição assincrona ajax
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'src/excluir/excluirColaborador.php',
            data: {
                'id_colaborador': idColaborador
            },
            success: function(retorno) {
                alert(retorno['mensagem'])

                if (retorno['status'] == 'sucesso') {

                    listarColaborador(); //Atualizar a listagem de colaboradores
                    window.location.reload()
                }
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        })
    }
}  
  </script>