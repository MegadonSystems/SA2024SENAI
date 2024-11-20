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
    <div class="modal-dialog" id="modalColaborador">
      <div class="modal-content">
        <form id="form_colaborador" onsubmit="salvar()">
          <div class="modal-header">
            <h4 class="modal-title">Colaborador</h4>
            <button id="fecharModal" type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i style="color:antiquewhite" class="bi bi-x-square"></i></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="txt_id" value="NOVO">

            <div class="form-group">
              <label><b>Nome</b></label>
              <input type="text" class="form-control" id="nome" required maxlength="255">
            </div>
            <div class="form-group">
              <label><b>E-Mail</b></label>
              <input type="email" class="form-control" id="email" required maxlength="255">
            </div>
            <div class="form-group">
              <label><b>CPF</b></label>
              <input type="text" class="form-control" id="cpf" required maxlength="14">
            </div>
            <div class="form-group">
              <label><b>Data Nascimento</b></label>
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

$(document).ready(function() {
    // Supondo que seu modal tenha a classe 'modal' e o formulário tenha o id 'myForm'
    $(document).click(function(event) {
        var $target = $(event.target);
        // Verifique se o clique foi fora do modal
        if (!$target.closest('.modal').length && !$target.is('.modal')) {
            // Resetar o formulário
            $('form')[0].reset();
            document.getElementById('txt_id').value = 'NOVO';
        }
    });
});

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
          if (retorno['status'] == 'sucesso') {
            var tabelaColaboradores = document.querySelector('#tabela-colaboradores tbody');
            tabelaColaboradores.innerHTML = ''

            var colaboradores = retorno['dados'];

            colaboradores.forEach(function(colaborador) {
              // var dataNascimento = new Date(colaborador['data_nascimento']);
              // var dataFormatada = dataNascimento.toLocaleDateString('pt-BR');

              // Uma solução loucura para arrumar 🤪
              let dataNascimento = colaborador['data_nascimento']
              let dataFormatada = dataNascimento.split('-').reverse().join('/')
              

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
            'id': idColaborador
        },
        success: function(retorno) {
            if (retorno['status'] == 'sucesso') {
                // Imprimir dados do colaborador no modal
                document.getElementById('txt_id').value = retorno['dados']['id_colaborador']
                document.getElementById('nome').value = retorno['dados']['nome']
                document.getElementById('cpf').value = retorno['dados']['cpf']
                document.getElementById('data_nascimento').value = retorno['dados']['data_nascimento']
                document.getElementById('email').value = retorno['dados']['email']

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
                'id': idColaborador,
            },
            success: function(retorno) {
                alert(retorno['mensagem'])

                if (retorno['status'] == 'sucesso') {

                    listarColaborador();
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