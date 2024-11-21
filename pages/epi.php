<div id="view">
  <div id="top2">
    <button onclick="abrirModal()" id="btn"> <i class="bi bi-plus-lg"></i> Cadastrar EPI</button>
  </div>

  <table class="main-table" id="tabela-epis" style="border-right: 1px solid black; border-bottom: 1px solid black">
    <thead>
      <tr style="background-color: rgb(44, 44, 44);; color: antiquewhite;">
        <th style="width:50px">#</th>
        <th style="width:230px">Nome</th>
        <th>Descrição</th>
        <th style="width:160px">Qtd. Estoque</th>
        <th style="width:115px">Imagem</th>
        <th style="width:140px">Cod. Barra</th>
        <th style="width:190px; border-bottom: none ">Ações</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>

  <div id="adicionar" class="modal fade">
    <div class="modal-dialog" id="modalEpi">
      <div class="modal-content">
        <form id="form_epi">
          <div class="modal-header">
            <h4 class="modal-title">EPI</h4>
            <button id="fecharModal" type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i style="color:antiquewhite" class="bi bi-x-square"></i></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="txt_id" value="NOVO">
            <input type="hidden" id="txt_imagem">
            
            <div class="form-group">
              <label><b>Nome</b></label>
              <input type="text" class="form-control" id="nome" required maxlength="255">
            </div>
            <div class="form-group">
              <label><b>Descrição</b></label>
              <textarea style="font-size: 0.9rem; height: 80px;" class="form-control" id="descricao" required maxlength="255"></textarea>
            </div>
            <div class="form-group">
              <label><b>Qtd. Estoque</b></label>
              <input type="number" class="form-control" id="estoque" required>
            </div>
            <div class="form-group">
              <label><b>Imagem</b></label>
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

    function listarEpi() {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/selecionar/selecionarEPI.php',
        success: function(retorno) {
          if (retorno['status'] == 'sucesso') {
            var tabelaEpis = document.querySelector('#tabela-epis tbody');
            tabelaEpis.innerHTML = '';
            var epis = retorno['dados'];
            epis.forEach(function(epi) {
              let codBar = epi['codigo_barra'] != '' ? `<a href="https://www.barcodesinc.com/generator/image.php?code=${epi['codigo_barra']}&style=197&type=C128B&width=300&height=100&xres=1&font=3" target="_blank">Ver Cod. Barra</a>`: 'Não Gerado'
              let imagem = epi['imagem']? `<a href="src/upload/${epi['imagem']}" target="_blank">Ver Imagem</a>`: 'Não Disponivel'

              var linha = document.createElement('tr');
              linha.innerHTML =
                `
              <td style="width:50px">${epi['id_epi']}</td>
              <td id="nomeFont" style="width:230px">${epi['nome']}</td>
              <td id="descricaoFont">${epi['descricao']}</td>
              <td style="width:160px">${epi['qtd_estoque']}</td>
              <td style="width:115px">${imagem}</td>
              <td style="width:140px; border-right: 1px solid black">${codBar}</td>
              <td style="width:190px; border-bottom: none; border-right: none" class='orgAcao'>  
              <a class='acao' href='#' title='Alterar'  onclick='carregarEpi(${epi['id_epi']})' style='margin-left:20px'><i class='bi bi-pencil-square'></i></i></i></a>
              <a class='acao' href='#' title='Excluir'  onclick='excluirEpi(${epi['id_epi']})' style='margin-left:30px'><i class='bi bi-trash3-fill'></i></a>
              <a class='acao' href='#' title='Gerar Cod. Barras' onclick='gerarCod(${epi['id_epi']})' style='margin-left:25px'><i class="bi bi-upc"></i></a>
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

    function carregarEpi(idEpi) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/carregar/carregarEpi.php',
        data: {
            'id': idEpi
        },
        success: function(retorno) {
          if (retorno['status'] == 'sucesso') {
            // Imprimir dados do usuário no modal

            document.getElementById('txt_id').value = retorno['dados']['id_epi']
            document.getElementById('nome').value = retorno['dados']['nome']
            document.getElementById('descricao').value = retorno['dados']['descricao']
            document.getElementById('estoque').value = retorno['dados']['qtd_estoque']
            document.getElementById('txt_imagem').value = retorno['dados']['imagem']

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
                'id': idEpi
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
            },
        })
      }
    }

    function gerarCod(idEpi) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/gerarCodBar.php',
        data: {
          'id': idEpi
        },
        success: function(retorno) {

                if (retorno['status'] == 'sucesso') {
                  alert(retorno['mensagem'])
                    
                  listarEpi(); //Atualizar a listagem de EPI's
                  // window.location.reload()
                }
            },
            error: function(erro) {
                alert('Ocorreu um erro na requisição: ' + erro);
            }
        })
  }
</script>