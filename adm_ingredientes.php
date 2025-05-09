<?php
include_once 'banco.php';

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($acao === 'inserir') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['txt_nome'] ?? null;
            $tipo = $_POST['txt_tipo'] ?? null;
            $imagem = $_POST['txt_imagem'] ?? null;
            if ($nome && $tipo && $imagem) {
                inserirIngrediente($nome, $tipo, $imagem);
                header('Location: index.php');
                exit;
            }
        }
        header('Location: index.php');
        exit;
    }
    if ($acao === 'deletar') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            excluirIngrediente($id);
            header('Location: index.php');
            exit;
        }
        header('Location: index.php');
        exit;
    }
}
header('Location: index.php');
exit;
