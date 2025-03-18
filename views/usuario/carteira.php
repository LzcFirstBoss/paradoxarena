<?php
include_once __DIR__ . '/../../helpers/protectuser.php';
requireAuth();

// Define o caminho do CSS específico para esta página
$customCSS = '../public/css/carteira/carteira.css'; // Caminho do CSS específico
$activePage = 'deposito';
// Inicia o buffer de saída
ob_start();
?>

<!-- inicio html -->

<div class="container">
   <div class="header">
        <h1>Carteira</h1>
   </div>
   <div class="infos_carteira">
      <div class="flex">
         <div class="saldo">
            <h3>Saldo Disponível</h3>
            <div class="saldo_infos">
               <span id="saldo">R$ <?php echo number_format($walletData['saldo'] ?? 0, 2, ',', '.'); ?><i class="bi bi-coin" id="iconcarteira"></i></span></span>
            </div>
            <hr>
            <div class="adicionarfundos">
               <a href="/paradoxarena/public/pagamentos" id="fundos"><i class="bi bi-plus"></i> Adicionar Fundos</a>
            </div>
         </div>
         <div class="info_pix">
            <h3>Informações da Carteira</h3>
               <div class="chavepix">
                  <span>Chave Pix Registrada</span>
                  <div class="chave">
                     <input type="text" value="<?php echo htmlspecialchars($walletData['chave_pix']); ?>" disabled> 
                     <i class="bi bi-copy" id="copy"></i>
                  </div>
               </div>
               <div class="acaorapida">
                  <span>Ações Rápidas</span>
                  <div class="acoes">
                     <div class="acao_depositar acao">
                        <span><i class="bi bi-arrow-up-right" id="depositar"></i> Depositar</span>
                     </div>
                     <div class="acao_sacar acao">
                        <span><i class="bi bi-arrow-down-left" id="sacar"></i> Sacar</span>
                     </div>
                     <div class="acao_atualizar acao">
                        <span><i class="bi bi-arrow-clockwise" id="atualizar"></i> Atualizar Saldo</span>
                     </div>
                  </div>
               </div>
         </div>
      </div>
      <div class="historico_trasacao">
         <div class="historico_header">
            <h3>Histórico de Transações</h3>
            <span>Acompanhe seus depósitos e saques</span>
         </div>
         <div class="table_historico">
         <table>
            <tr>
               <th>Tipo</th>
               <th>Valor</th>
               <th>Data</th>
               <th>Status</th>
               <th>Método</th>
            </tr>
            <tr>
               <td><i class="bi bi-arrow-up-right" id="depositar"></i> Depósitar</td>
               <td id="depositar">+R$ 250,00</td>
               <td style="color: rgb(156 163 176);">15/15/2001, 03:23</td>
               <td id="depositar">Aprovada</td>
               <td style="color: rgb(156 163 176);">PIX</td>
            </tr>
            <tr>
               <td><i class="bi bi-arrow-up-right" id="depositar"></i> Depositar</td>
               <td id="depositar">+R$ 250,00</td>
               <td style="color: rgb(156 163 176);">15/15/2001, 03:23</td>
               <td id="depositar">Aprovada</td>
               <td style="color: rgb(156 163 176);">PIX</td>
            </tr>
         </table>
         </div>
      </div>
   </div>
</div>

<!--fim html -->

<?php
// Captura todo o conteúdo gerado e armazena na variável $content
$content = ob_get_clean();

// Agora, inclui o template principal, que usará a variável $customCSS e $content
include_once __DIR__ . '/../templates/main.php';
?>
