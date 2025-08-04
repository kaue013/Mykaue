<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    $upload_dir = "uploads/";
    $documento = $_FILES['documento']['name'];
    $temp = $_FILES['documento']['tmp_name'];

    $destino = $upload_dir . basename($documento);

    if (move_uploaded_file($temp, $destino)) {
        echo "Cadastro enviado com sucesso!<br>";
        echo "Nome: $nome<br>CPF: $cpf<br>Documento salvo em: $destino";
    } else {
        echo "Erro ao enviar documento.";
    }
}
?>
