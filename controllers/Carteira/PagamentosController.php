<?php
require __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../../Controllers/usuario/BaseController.php';


use Efi\Exception\EfiException;
use Efi\EfiPay;

class PagamentosController  extends BaseController{
    public function index() {

        $userData = $this->getUserData();
        $walletData = $this->getWalletData();

        if (!isset($_POST['valor'])) {
            echo json_encode(['erro' => 'Valor não informado.']);
          return;
        }
        
        $cpf = $userData['cpf'];
        $usuario = $userData['nome_completo'];
        

        $options = [
            "clientId" => "Client_Id_1c60bac4983f92e49ee2d6844b4356e5656d16b7",
            "clientSecret" => "Client_Secret_0bdc2d5f8c36e406e5f6679eecbfccc29d748b90",
            "certificate" => realpath(__DIR__ . "/../../efibank/certificado/certificado.p12"),
            "sandbox" => false 
        ];

        $cobBody = [
            'calendario' => ['expiracao' => 3600],
            'devedor' => [
                'cpf' => ''. $cpf .'',
                'nome' => ''.$usuario.''
            ],
            'valor' => ['original' => number_format($valor, 2, '.', '')],
            'chave' => '65603fca-098f-4e04-ade2-017a55f88aa3',
            'solicitacaoPagador' => 'Depósito na Paradox Arena R$ '.$valor.''
        ];

        try {
            $api = new EfiPay($options);
            $response = $api->pixCreateImmediateCharge([], $cobBody);

            if (isset($response['pixCopiaECola'])) {
                echo json_encode([
                    'qrcode' => $response['pixCopiaECola'],
                    'pixCopiaECola' => $response['pixCopiaECola']
                ]);
            } else {
                echo json_encode(['erro' => 'Erro ao gerar Pix.']);
            }
        } catch (EfiException $e) {
            echo json_encode(['erro' => $e->errorDescription]);
        } catch (Exception $e) {
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }
}

