<?php
// Verifica se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se todos os campos obrigatórios estão presentes
    if (isset($_POST['nome'], $_POST['cpf'], $_FILES['documento'])) {
        
        $nome = trim($_POST['nome']);
        $cpf = trim($_POST['cpf']);
        $arquivo = $_FILES['documento'];

        // Validação básica do CPF
        if (!preg_match("/^[0-9]{11}$/", $cpf)) {
            exit("CPF inválido. Digite apenas números, com 11 dígitos.");
        }

        // Validação do arquivo enviado
        $permitidos = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($arquivo['type'], $permitidos)) {
            exit("Arquivo inválido. Envie um PDF, JPG ou PNG.");
        }

        if ($arquivo['error'] !== 0) {
            exit("Erro ao enviar o arquivo.");
        }

        // Garante que a pasta uploads exista
        $pastaUploads = "uploads/";
        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0755, true);
        }

        // Cria nome único para o arquivo
        $ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid("curriculo_", true) . "." . $ext;
        $caminhoFinal = $pastaUploads . $nomeArquivo;

        // Move o arquivo para a pasta final
        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
            exit("Erro ao salvar o currículo.");
        }

        // Salva os dados no arquivo dados.txt
        $linha = "$nome;$cpf;$nomeArquivo\n";
        file_put_contents("dados.txt", $linha, FILE_APPEND | LOCK_EX);

        echo "<h2>Cadastro enviado com sucesso!</h2>";
        echo "<p>Nome: $nome</p>";
        echo "<p>CPF: $cpf</p>";
        echo "<p>Currículo salvo como: $nomeArquivo</p>";
        echo "<a href='index.html'>Voltar</a>";

    } else {
        echo "Preencha todos os campos corretamente.";
    }
} else {
    echo "Acesso inválido.";
}
?>
