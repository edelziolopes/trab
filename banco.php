<?php
function conectar() {
    $mysqli = new mysqli("localhost", "root", "", "projeto");     
    if ($mysqli->connect_error) {
        die("Falha: ".$mysqli->connect_error);
    }    
    return $mysqli;
}

function inserirUsuario($nome, $email, $senha, $foto) {
    $mysqli = conectar();
    $stmt = $mysqli->prepare("
        INSERT INTO tb_usuario (nome, email, senha, foto) 
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $foto);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
function listarUsuario() {
    $mysqli = conectar();
    $sql = "SELECT * FROM tb_usuario";
    $result = $mysqli->query($sql);
    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[] = $row;
    }
    $mysqli->close();
    return $array;
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($acao === 'inserirUsuario') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['txt_nome'] ?? null;
            $email = $_POST['txt_email'] ?? null;
            $senha = $_POST['txt_senha'] ?? null;
            $foto = $_POST['txt_foto'] ?? null;
            if ($nome && $email && $senha && $foto) {
                inserirUsuario($nome, $email, $senha, $foto);
                header('Location: index.php');
                exit;
            }
        }
        header('Location: index.php');
        exit;
    }
}

?>