<?php
    session_start();

    // Dados de login (exemplo simples, em produção use um banco de dados)
    $usuario_correto = "root";
    $senha_correta = "Alex01lo";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_digitado = $_POST['usuario'];
    $senha_digitada = $_POST['senha'];

    // Verifica se o usuário e a senha estão corretos
    if ($usuario_digitado === $usuario_correto && $senha_digitada === $senha_correta) {
        // Credenciais corretas, inicia a sessão e redireciona
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario_digitado;
        header("Location: principal.php");
        exit;
    } else {
        // Credenciais incorretas, define uma mensagem de erro e redireciona para o login
        $_SESSION['erro_login'] = "Usuário ou senha incorretos.";
        header("Location: index.php");
        exit;
    }
} else {
    // Acesso direto a este arquivo sem o formulário, redireciona para o login
    header("Location: index.php");
    exit;
}