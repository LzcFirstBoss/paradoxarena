<?php

class TesteController{

    /**
 * Paga um QR Code Pix via API.
 *
 * @param string $token         Token de autorização (Bearer).
 * @param string $idEnvio       O identificador único do envio.
 * @param array  $pagadorData   Array associativo com os dados do pagador.
 *                              Exemplo: ['chave' => 'a1f4102e-a446-4a57-bcce-6fa48899c1d1', 'infoPagador' => 'Pagamento de QR Code via API Pix']
 * @param string $pixCopiaECola Conteúdo do Pix copia e cola conforme especificado pela API.
 *
 * @return array                Dados da resposta da API.
 *
 * @throws Exception            Se ocorrer erro na requisição ou se a resposta indicar falha.
 */
function pagarQRCodePix($token, $idEnvio, $pagadorData, $pixCopiaECola) {
    // Endpoint conforme a documentação
    $url = "https://api.bancoefi.com/v2/gn/pix/" . urlencode($idEnvio) . "/qrcode";

    // Monta o payload em JSON
    $payload = json_encode([
        'pagador'       => $pagadorData,
        'pixCopiaECola' => $pixCopiaECola
    ]);

    // Inicializa o cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);

    // Executa a requisição
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception("Erro na requisição: " . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verifica se a resposta foi um sucesso (201, conforme a documentação)
    if ($httpCode != 201) {
        throw new Exception("Erro ao pagar QR Code Pix. Código HTTP: $httpCode. Resposta: $response");
    }

    return json_decode($response, true);
}

// Exemplo de uso:
$token = 'seu_token_de_acesso'; // Obtenha o token conforme a autenticação da API
$idEnvio = '12453567890123456789'; // Identificador único para este envio, deve ser único para cada transação
$pagadorData = [
    'chave'      => 'a1f4102e-a446-4a57-bcce-6fa48899c1d1', // Chave Pix do pagador
    'infoPagador'=> 'Pagamento de QR Code via API Pix'
];
$pixCopiaECola = "00020101021226830014BR.GOV.BCB.PIX2561qrcodespix.sejaefi.com.br/v2 41e0badf811a4ce6ad8a80b306821fce5204000053000065802BR5905EFISA6008SAOPAULO60070503***61040000";

try {
    $resultado = pagarQRCodePix($token, $idEnvio, $pagadorData, $pixCopiaECola);
    echo "Pagamento enviado com sucesso!<br>";
    echo "ID do Envio: " . htmlspecialchars($resultado['idEnvio']);
    // Outras informações da resposta podem ser exibidas conforme necessário.
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

}

?>
