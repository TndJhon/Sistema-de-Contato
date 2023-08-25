<?php
$mensagem = "Preencha os dados do formulário";
$nome = "";
$email = "";
$msg = "";

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('BASE', 'login');

if (isset($_POST["nome"], $_POST["email"], $_POST["msg"])) {
    // Criação da conexão com o banco de dados
    $conexao = new mysqli(HOST, USER, PASS, BASE);

    // Verificar se a conexão foi estabelecida com sucesso
    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $msg = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_STRING);

    if (!$nome || !$email || !$msg) {
        $mensagem = "Dados inválidos";
    } else {
        // Usar marcadores de posição (?) em vez de nomes
        $stm = $conexao->prepare('INSERT INTO contato (nome, email, msg) VALUES (?, ?, ?)');
        $stm->bind_param('sss', $nome, $email, $msg); // 'sss' indica que são todos strings

        if ($stm->execute()) {
            $mensagem = "Mensagem Enviada com Sucesso!";
        } else {
            $mensagem = "Erro ao enviar mensagem: " . $conexao->error;
        }
    }

    // Fechar a conexão com o banco de dados
    $conexao->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <form method="POST">
            <label for="name">Nome</label>
            <input type="text" name="nome" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="text">Mensagem</label>
            <textarea name="msg"></textarea>

            <button type="submit">Enviar</button>
        </form>
        <div class="mensagem">
            <?=$mensagem?>
        </div>
    </main>
</body>
</html>