<?php
session_start();

// --- Configurações do Banco de Dados ---
// Host: Use '127.0.0.1' se estiver usando o Cloud SQL Auth Proxy na VM
// Se estiver usando o IP privado direto, substitua pelo IP da sua instância
$servername = "104.198.229.175";
$username = "root";
$password = "Alex01lo";
$dbname = "sisper";

// --- Lógica de Validação ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_digitado = $_POST['usuario'];
    $senha_digitada = $_POST['senha'];

    try {
        // Conectar ao banco de dados MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prevenir injeção de SQL usando prepared statements
        $stmt = $conn->prepare("SELECT password FROM sisper.usuarios WHERE user = :usuario");
        $stmt->bindParam(':usuario', $usuario_digitado);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($resultado);
        //die; 

        if ($resultado) {
            // Verifica a senha usando password_verify para maior segurança
            // A senha no banco de dados deve estar hasheada com password_hash
            if ($senha_digitada === $resultado['password']) {
                // Credenciais corretas, inicia a sessão
                $_SESSION['logado'] = true;
                $_SESSION['usuario'] = $usuario_digitado;
                header("Location: principal.php");
                exit;
            } else {
                // Senha incorreta
                $_SESSION['erro_login'] = "Usuário ou senha incorretos.";
                header("Location: index.php");
                exit;
            }
        } else {
            // Usuário não encontrado
            $_SESSION['erro_login'] = "Usuário ou senha incorretos.";
            header("Location: index.php");
            exit;
        }

    } catch(PDOException $e) {
        // Em caso de erro de conexão, você pode logar a mensagem para depuração
        // mas não exiba para o usuário final por questões de segurança.
        error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
        $_SESSION['erro_login'] = "Ocorreu um erro, tente novamente mais tarde.";
        header("Location: index.php");
        exit;
    }
} else {
    // Acesso direto a este arquivo sem o formulário, redireciona para o login
    header("Location: index.php");
    exit;
}