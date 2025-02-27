<?

class LoginController{

    public function logarCadastro() {
        require_once __DIR__ . '/../../views/auth/login.php';
    }

  public function LogarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /paradoxarena/public/cadastro');
            exit;
        }
}

}
?>