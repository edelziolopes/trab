<?php
$usuarioLogado = isset($_COOKIE['usuario_id'], $_COOKIE['usuario_nome'], $_COOKIE['usuario_email']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .foto-perfil {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if ($usuarioLogado): ?>
                <div class="card text-center shadow">
                    <div class="card-body">
                        <?php if (!empty($_COOKIE['usuario_foto'])): ?>
                            <img src="imagens/<?= htmlspecialchars($_COOKIE['usuario_foto']) ?>" class="foto-perfil mb-3" alt="Foto de perfil">
                        <?php endif; ?>
                        <h3 class="card-title">Bem-vindo, <?= htmlspecialchars($_COOKIE['usuario_nome']) ?>!</h3>
                        <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($_COOKIE['usuario_id']) ?></p>
                        <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($_COOKIE['usuario_email']) ?></p>
                        <a href="banco.php?acao=logoff" class="btn btn-outline-danger mt-3">Sair</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center shadow">
                    <h4 class="alert-heading">Você não está conectado!</h4>
                    <p>
                        <a href="login.php" class="btn btn-primary me-2">Fazer Login</a>
                        <a href="registro.php" class="btn btn-success">Cadastrar-se</a>
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>
