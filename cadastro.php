<?php
// Pasta onde os currículos serão salvos
$pastaUploads = 'uploads/';

// Tipos permitidos (mime types)
$tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário com segurança
    $nome = htmlspecialchars(trim($_POST['nome']));
    $cpf = htmlspecialchars(trim($_POST['cpf']));

    // Verifica se o arquivo foi enviado corretamente
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $arquivoTmp = $_FILES['documento']['tmp_name'];
        $tipo = mime_content_type($arquivoTmp);

        // Verifica tipo permitido
        if (!in_array($tipo, $tiposPermitidos)) {
            die('Tipo de arquivo não permitido. Envie PDF, JPG ou PNG.');
        }

        // Cria pasta se não existir
        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0755, true);
        }

        // Gera nome único com extensão minúscula
        $extensao = strtolower(pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION));
        $nomeUnico = uniqid('curriculo_', true) . '.' . $extensao;

        // Move o arquivo para a pasta uploads
        $caminhoFinal = $pastaUploads . $nomeUnico;
        if (!move_uploaded_file($arquivoTmp, $caminhoFinal)) {
            die('Erro ao salvar o arquivo.');
        }

        // Monta a linha de dados
        $linha = "$nome | $cpf | $nomeUnico\n";

        // Salva com LOCK_EX (evita concorrência)
        file_put_contents('dados.txt', $linha, FILE_APPEND | LOCK_EX);

        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao enviar o arquivo.";
    }
} else {
    echo "Requisição inválida.";
}
?>
