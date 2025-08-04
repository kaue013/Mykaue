<?php
// Pasta onde os currículos serão salvos
$pastaUploads = 'uploads/';

// Tipos permitidos (mime types)
$tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome = htmlspecialchars(trim($_POST['nome']));
    $cpf = htmlspecialchars(trim($_POST['cpf']));

    // Verifica se foi enviado um arquivo
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $arquivoTmp = $_FILES['documento']['tmp_name'];
        $tipo = mime_content_type($arquivoTmp);

        if (!in_array($tipo, $tiposPermitidos)) {
            die('Tipo de arquivo não permitido. Envie PDF, JPG ou PNG.');
        }

        // Cria nome único para o arquivo
        $extensao = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
        $nomeUnico = uniqid('curriculo_', true) . '.' . $extensao;

        // Move o arquivo para a pasta uploads
        if (!move_uploaded_file($arquivoTmp, $pastaUploads . $nomeUnico)) {
            die('Erro ao salvar o arquivo.');
        }

        // Monta a linha para salvar no txt
        $linha = "$nome | $cpf | $nomeUnico\n";

        // Salva no arquivo de dados com LOCK_EX
        file_put_contents('dados.txt', $linha, FILE_APPEND | LOCK_EX);

        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao enviar o arquivo.";
    }
} else {
    echo "Requisição inválida.";
}
?>
