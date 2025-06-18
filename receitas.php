<?php
require_once 'banco.php';
$receitas = listarReceita();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Receitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Cadastro de Receitas</h2>

  <form method="post" action="adm_receitas.php?acao=inserir" class="row g-3 mb-5">
    <div class="col-12">
      <label for="nome" class="form-label">Titulo</label>
      <input type="text" class="form-control" id="nome" name="txt_titulo" placeholder="Ex: Tomate">
    </div>
    <div class="col-12">
      <label for="tipo" class="form-label">Descricao</label>
      <input type="text" class="form-control" id="tipo" name="txt_descricao" placeholder="Ex: Legume">
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Adicionar Receita</button>
    </div>
  </form>

  <h4 class="mb-3">Lista de Receitas</h4>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>id</th>
        <th>Titulo</th>
        <th>Descricao</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($receitas)): ?>
      <tr>
          <td colspan="3" class="text-center">Nenhuma categoria</td>
      </tr>
      <?php else: ?>
        <?php foreach ($receitas as $receita): ?>
        <tr>
            <td><?php echo $receita['id']; ?></td>
            <td><?php echo $receita['titulo']; ?></td>
            <td><?php echo $receita['descricao']; ?></td>
            <td><a class="btn btn-danger btn-sm" href="adm_receitas.php?acao=deletar&id=<?php echo $ingrediente['id']; ?>">Excluir</a></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
