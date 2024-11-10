<div id="view">
  <div id="top2">
    <button onclick="abrirModal()" id="btn"> <i class="bi bi-plus-lg"></i> Cadastrar EPI</button>
  </div>

  <table class="main-table" id="tabela-epis" style="border-right: 1px solid black; border-bottom: 1px solid black">
    <thead>
      <tr style="background-color: rgb(44, 44, 44);; color: antiquewhite;">
        <th style="width:50px">#</th>
        <th style="width:250px">Nome</th>
        <th>Descrição</th>
        <th style="width:170px">Qtd. Estoque</th>
        <th style="width:150px">Imagem</th>
        <th style="width:150px">Cod. Barras</th>
        <th style="width:160px; border-bottom: none ">Ações</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>

  <div id="adicionar" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="form_produto">
          <div class="modal-header">
            <h4 class="modal-title">Cadastro de EPI</h4>
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
              <label>Descrição</label>
              <input type="text" class="form-control" id="descricao" required>
            </div>
            <div class="form-group">
              <label>Qtd. Estoque</label>
              <input type="text" class="form-control" id="estoque" required>
            </div>
            <div class="form-group">
              <label>Imagem</label>
              <input type="file" class="form-control" id="imagem" required>
            </div>

          </div>
          <div class="modal-footer">
            <button onclick="salvar()" type="submit" class="btn btn-success">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    window.onload = function() {
      listarEpi();
    }


    function listarEpi() {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/selecionar/selecionarEPI.php',
        success: function(retorno) {
          console.log(retorno)
          if (retorno['status'] == 'sucesso') {
            var tabelaEpis = document.querySelector('#tabela-epis tbody');
            var epis = retorno['dados'];
            epis.forEach(function(epi) {
              var linha = document.createElement('tr');
              linha.innerHTML =
                `
              <td style="width:50px">${epi['id_epi']}</td>
              <td id="nomeFont" style="width:250px">${epi['nome']}</td>
              <td id="descricaoFont">${epi['descricao']}</td>
              <td style="width:170px">${epi['qtd_estoque']}</td>
              <td style="width:150px">${epi['imagem']}</td>
              <td style="width:150px; border-right: 1px solid black">${epi['cod_barra']}</td>
              <td style="width:160px; border-bottom: none; border-right: none"" class='orgAcao'>  
              <a class='acao' href='#' title='Alterar' onclick='excluirEpi(${epi['id_epi']})' style='margin-left:30px'><i class='bi bi-pencil-square'></i></i></i></a>
              <a class='acao' href='#' title='Excluir' onclick='carregarEpi(${epi['id_epi']})' style='margin-left:30px'><i class='bi bi-trash3-fill'></i></a>
              </td>
            `

              tabelaEpis.appendChild(linha)
            })

          } else {
            var linha = document.createElement('tr')
            linha.innerHTML = `<tr> <td colspan='8'> Nenhum EPI cadastrado! </td> </tr>`
            document.querySelector('#tabela-epis tbody').appendChild(linha)
          }

        },
        error: function(erro) {
          console.error('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }

    function carregarEpi(idEpi){
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/carregar/carregarEpi.php',
        data: {
            'id_epi': idEpi
        },
        success: function(retorno) {
            if (retorno['status'] == 'sucesso') {
                // Imprimir dados do usuário no modal

                document.getElementById('id').value = retorno['dados']['id_epi']
                document.getElementById('nome').value = retorno['dados']['nome']
                document.getElementById('estoque').value = retorno['dados']['qtd_estoque']
                document.getElementById('imagem').value = retorno['dados']['imagem']

            } else {
                alert(retorno['mensagem'])
            }
        },
        error: function(erro) {
            alert('Ocorreu um erro na requisição: ' + erro);
        }
    })
    }

    function excluirEpi(idEpi) {
    //Confirma se o usuário quer excluir ou n
    let confirmar = confirm("Deseja realmente excluir esse EPI?");

    if (confirmar) {
        //Requisição assincrona ajax
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'src/excluir/excluirEpi.php',
            data: {
                'id_epi': idEpi
            },
            success: function(retorno) {
                alert(retorno['mensagem'])

                if (retorno['status'] == 'sucesso') {

                    listarEpi(); //Atualizar a listagem de EPI's
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