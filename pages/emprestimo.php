   <div id="view">
       <div id="top2">
           <button onclick="abrirModal()" id="btn"> <i class="bi bi-plus-lg"></i> Cadastrar Empréstimo</button>
       </div>
       <table class="main-table" id="tabela-emprestimos">
           <thead>
               <tr style="background-color: rgb(44, 44, 44);; color: antiquewhite;">
                   <th style="width:50px">#</th>
                   <th>Descrição</th>
                   <th style="width:140px">Quantidade</th>
                   <th style="width:120px">Status</th>
                   <th style="width:130px">Retirada</th>
                   <th style="width:150px">Devolução</th>
                   <th style="width:240px">Colaborador</th>
                   <th style="width:190px">EPI</th>
                   <th style="width:150px; border-bottom: none">Ações</th>
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
                           <h4 class="modal-title">Cadastro de Empréstimo</h4>
                           <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                       </div>
                       <div class="modal-body">
                           <input type="hidden" id="txt_id" value="novo">
                           <input type="hidden" id="txt_nome_imagem">

                           <div class="form-group">
                               <label>Descrição</label>
                               <input type="text" class="form-control" id="descricao" required maxlength="255">
                           </div>

                           <div class="form-group">
                               <label>Quantidade:</label>
                               <input type="number" class="form-control" id="quantidade">
                           </div> <br>

                           <div class="form-group">
                               <label>Data de Retirada</label>
                               <input type="date" class="form-control" id="data_retirada" required>
                           </div>
                           <div class="form-group">
                               <label>Data de Devolução</label>
                               <input type="date" class="form-control" id="data_devolucao">
                           </div> <br>
                           <div class="form-group">
                               <label>Colaborador:</label>
                               <select id="fk_colaborador">
                                   <option value="">Selecione...</option>
                               </select>

                           </div> <br>
                           <div class="form-group">
                               <label>EPI:</label>
                               <select id="fk_epi">
                                   <option value="">Selecione...</option>
                               </select>

                           </div>
                       </div>
                       <div class="modal-footer">
                           <button onclick="salvar()" type="submit" class="btn btn-success">Salvar</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>

       <div id="adicionar" class="modal fade">
           <div class="modal-dialog">
               <div class="modal-content">
                   <label id="descricao" for=""></label>
               </div>
           </div>
       </div>

       <script>
           window.onload = function() {
               listarEmprestimo();
               listarFK();
           }

           function listarFK() {
               $.ajax({
                   type: 'post',
                   dataType: 'json',
                   url: 'src/listarFK.php',
                   success: function(retorno) {
                       const dadosC = retorno['dadosC']
                       const dadosE = retorno['dadosE']

                       selectColaborador = document.getElementById('fk_colaborador')
                       selectEpi = document.getElementById('fk_epi')


                       dadosC.forEach(function(colaborador) {
                           const option = document.createElement('option')
                           option.value = colaborador.id_colaborador;
                           option.textContent = colaborador.nome;
                           selectColaborador.appendChild(option)
                       })

                       dadosE.forEach(function(epi) {
                           const option = document.createElement('option')
                           option.value = epi.id_epi;
                           option.textContent = epi.nome;
                           selectEpi.appendChild(option)
                       })
                   },
                   error: function(erro) {
                       alert('Ocorreu um erro na requisição: ' + erro);
                   }
               });
           }

           function listarEmprestimo() {
               $.ajax({
                   type: 'post',
                   dataType: 'json',
                   url: 'src/selecionar/selecionarEmprestimos.php',
                   success: function(retorno) {
                       if (retorno['status'] == 'sucesso') {
                           var tabelaEmprestimos = document.querySelector('#tabela-emprestimos tbody');
                           var emprestimos = retorno['dados'];
                           emprestimos.forEach(function(emprestimo) {
                               if (emprestimo.status == 1) {
                                   var status = 'Ativo'
                               } else {
                                   var status = 'Concluído'
                               }

                               var dataR = new Date(emprestimo['data_retirada']);
                               var rFormatada = dataR.toLocaleDateString('pt-BR');

                               if(emprestimo['data_devolucao'] !== '0000-00-00'){
                                    var dataD = new Date(emprestimo['data_devolucao']);
                                    var dFormatada = dataD.toLocaleDateString('pt-BR');
                               }else{
                                    var dFormatada = 'Não Devolvido';
                               }
                               


                               var linha = document.createElement('tr');
                               linha.innerHTML =
                                   `
                                <td style="width:50px">${emprestimo['id_emprestimo']}</td>
                                <td id='descricaoFont'>${emprestimo['descricao']}</td>
                                <td style="width:140px">${emprestimo['quantidade']}</td>
                                <td style="width:120px">${status}</td>
                                <td style="width:130px">${rFormatada}</td>
                                <td style="width:150px">${dFormatada}</td>
                                <td style="width:240px">${emprestimo['id_colaborador']}</td>
                                <td style="width:190px">${emprestimo['id_epi']}</td>
                                <td style="width:150px; border: none" class="orgAcao">  
                                <a class='acao' href='#' onclick='devolverEPI(${emprestimo['id_emprestimo']}, ${emprestimo['statusE']} )' title='Devolver EPI' style='margin-left:43%'> <i class='bi bi-arrow-left-square-fill'></i> </a>
                                </td>
                               `

                               tabelaEmprestimos.appendChild(linha)
                           })

                       } else {
                           var linha = document.createElement('tr')
                           linha.innerHTML = `<tr> <td colspan='8'> Nenhum empréstimo cadastrado! </td> </tr>`
                           document.querySelector('#tabela-emprestimos tbody').appendChild(linha)
                       }

                   },
                   error: function(erro) {
                       console.error('Ocorreu um erro na requisição: ' + erro);
                   }
               });
           }

           function devolverEPI(id, status) {
               console.log(status)
               if (status == 2) {
                   alert('Empréstimo já foi finalizado, impossível devolver EPI!')

               } else {
                   var confirmar = confirm('Tem certeza que deseja devolver o EPI?')
                   if (confirmar) {
                       $.ajax({
                           type: 'post',
                           dataType: 'json',
                           url: 'src/devolverEPI.php',
                           data: {
                               'id': id
                           },
                           success: function(retorno) {
                               console.log(retorno)
                               if (retorno['status'] == 'sucesso') {
                                   alert(retorno['mensagem'])
                                   location.reload()
                               } else {

                               }

                           },
                           error: function(erro) {
                               console.error('Ocorreu um erro na requisição: ' + erro);
                           }
                       })
                   }
               }
           }
       </script>