<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      max-width: 500px;
      width: 100%;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .icon-option {
      display: inline-block;
      padding: 15px;
      border-radius: 10px;
      border: 2px solid transparent;
      text-align: center;
      width: 100px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .icon-option i {
      font-size: 40px;
      color: #555;
    }

    input[type="radio"] {
      display: none;
    }

    input[type="radio"]:checked + label.icon-option {
      border-color: #007bff;
      background-color: #e9f5ff;
    }

    input[type="radio"]:checked + label.icon-option i {
      color: #007bff;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Cadastro de Usuário</h2>
    <form action="banco.php?acao=inserirUsuario" method="POST">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="txt_nome" id="nome" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="txt_email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="txt_senha" id="senha" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Escolha um avatar</label>
        <div class="d-flex flex-wrap justify-content-between gap-2">
          <!-- Avatar 1 -->
          <div>
            <input type="radio" id="avatar1" name="txt_foto" value="user" required>
            <label for="avatar1" class="icon-option">
              <i class="fas fa-user"></i>
            </label>
          </div>

          <!-- Avatar 2 -->
          <div>
            <input type="radio" id="avatar2" name="txt_foto" value="user-tie">
            <label for="avatar2" class="icon-option">
              <i class="fas fa-user-tie"></i>
            </label>
          </div>

          <!-- Avatar 3 -->
          <div>
            <input type="radio" id="avatar3" name="txt_foto" value="user-ninja">
            <label for="avatar3" class="icon-option">
              <i class="fas fa-user-ninja"></i>
            </label>
          </div>

          <!-- Avatar 4 -->
          <div>
            <input type="radio" id="avatar4" name="txt_foto" value="user-astronaut">
            <label for="avatar4" class="icon-option">
              <i class="fas fa-user-astronaut"></i>
            </label>
          </div>

          <!-- Avatar 5 -->
          <div>
            <input type="radio" id="avatar5" name="txt_foto" value="user-secret">
            <label for="avatar5" class="icon-option">
              <i class="fas fa-user-secret"></i>
            </label>
          </div>

          <!-- Avatar 6 -->
          <div>
            <input type="radio" id="avatar6" name="txt_foto" value="user-graduate">
            <label for="avatar6" class="icon-option">
              <i class="fas fa-user-graduate"></i>
            </label>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-3">Cadastrar</button>
    </form>
  </div>
</body>
</html>
