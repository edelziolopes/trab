<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e0f7fa, #d1c4e9);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      max-width: 400px;
      width: 100%;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
    }

    .register-link a {
      text-decoration: none;
      color: #007bff;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Login</h2>
    <form action="validar_login.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" id="senha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="register-link">
      <p>Não tem uma conta? <a href="registro.php">Cadastre-se aqui</a></p>
    </div>
  </div>
</body>
</html>
