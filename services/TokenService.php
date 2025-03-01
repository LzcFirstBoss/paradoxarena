<?php
// services/TokenService.php

class TokenService {
    /**
     * Gera um token único a partir de uma string base (por exemplo, CPF) e define o tempo de expiração.
     *
     * @param string $base Valor base para o token, por exemplo, o CPF do usuário.
     * @param string $expirationInterval Intervalo para expiração do token (padrão '+10 minutes').
     * @return array Array contendo o token gerado e sua data de expiração (chaves 'token' e 'expira_em').
     */
    public function generateToken($base, $expirationInterval = '+10 minutes') {
        $token = $base . bin2hex(random_bytes(32));
        $expiraEm = (new DateTime($expirationInterval))->format('Y-m-d H:i:s');
        return [
            'token' => $token,
            'expira_em' => $expiraEm
        ];
    }

    public function generateVerificationCode($length = 6) {
        // Gera um número aleatório entre 0 e o maior valor com $length dígitos, e preenche com zeros à esquerda se necessário.
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
