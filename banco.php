<?php
function conectar() {
    return new mysqli("localhost", "root", "", "projeto");
}

function inserirUsuario($nome, $email, $senha, $foto, $nivel = 2) {
    $conn = conectar();
    $stmt = $conn->prepare("INSERT INTO tb_usuario (nome, email, senha, foto, id_nivel) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nome, $email, $senha, $foto, $nivel);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function listarUsuarios() {
    $conn = conectar();
    $res = $conn->query("SELECT * FROM tb_usuario");
    $dados = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $dados;
}

function loginUsuario($email, $senha) {
    $conn = conectar();
    $stmt = $conn->prepare("SELECT id, nome, email, foto 
    FROM tb_usuario 
    WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $res = $stmt->get_result();
    $usuario = $res->fetch_assoc();
    $stmt->close();
    $conn->close();
    if ($usuario) {
        setcookie('usuario_id', $usuario['id'], time() + 604800, '/');
        setcookie('usuario_nome', $usuario['nome'], time() + 604800, '/');
        setcookie('usuario_email', $usuario['email'], time() + 604800, '/');
        setcookie('usuario_foto', $usuario['foto'], time() + 604800, '/');
        return true;
    }
    return false;
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($acao === 'inserirUsuario') {
            $nome = $_POST['txt_nome'] ?? null;
            $email = $_POST['txt_email'] ?? null;
            $senha = $_POST['txt_senha'] ?? null;
            $foto = $_POST['txt_foto'] ?? null;
            if ($nome && $email && $senha && $foto) {
                inserirUsuario($nome, $email, $senha, $foto);
            } 
        }
        if ($acao === 'loginUsuario') {
            $email = $_POST['txt_email'] ?? null;
            $senha = $_POST['txt_senha'] ?? null;
            if ($email && $senha) {
                loginUsuario($email, $senha);
            }
        }
    }
    if ($acao === 'logoff') {
        setcookie('usuario_id', '', time() - 3600, '/');
        setcookie('usuario_nome', '', time() - 3600, '/');
        setcookie('usuario_email', '', time() - 3600, '/');
        setcookie('usuario_foto', '', time() - 3600, '/');
    }
    header('Location: index.php');
    exit;

}

?>