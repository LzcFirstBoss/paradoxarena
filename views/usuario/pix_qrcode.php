<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento via Pix</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <h2>Escaneie o QR Code para pagar</h2>
    <div id="qrcode"></div>

    <h3>Ou copie e cole:</h3>
    <input type="text" id="pixCopiaECola" value="<?= htmlspecialchars($pixCopiaECola); ?>" readonly>
    <button onclick="copiarPix()">Copiar Código</button>

    <script>
        let pixCopiaECola = "<?= $pixCopiaECola; ?>";

        new QRCode(document.getElementById("qrcode"), {
            text: pixCopiaECola,
            width: 256,
            height: 256
        });

        function copiarPix() {
            let input = document.getElementById("pixCopiaECola");
            input.select();
            document.execCommand("copy");
            alert("Código Pix copiado!");
        }
    </script>

</body>
</html>
