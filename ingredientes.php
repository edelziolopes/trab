<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Ingredientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Cadastro de Ingredientes</h2>

  <form method="post" action="adm_ingredientes.php?acao=inserir" class="row g-3 mb-5">
    <div class="col-12">
      <label for="nome" class="form-label">Nome</label>
      <input type="text" class="form-control" id="nome" name="txt_nome" placeholder="Ex: Tomate">
    </div>
    <div class="col-12">
      <label for="tipo" class="form-label">Tipo</label>
      <input type="text" class="form-control" id="tipo" name="txt_tipo" placeholder="Ex: Legume">
    </div>
    <div class="col-12">
      <label for="imagem" class="form-label">Imagem</label>
      <input type="text" class="form-control" id="imagem" name="txt_imagem">
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Adicionar Ingrediente</button>
    </div>
  </form>

  <h4 class="mb-3">Lista de Ingredientes</h4>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Imagem</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($array)): ?>
      <tr>
          <td colspan="3" class="text-center">Nenhuma categoria</td>
      </tr>
      <?php else: ?>
        <?php foreach ($categorias as $categoria): ?>
        <tr>
            <td><img src="<?php echo $categoria['id']; ?>" class="rounded"></td>
            <td><?php echo $categoria['nome']; ?></td>
            <td><?php echo $categoria['descricao']; ?></td>
            <td><a class="btn btn-danger btn-sm" href="adm_cat.php?id=<?php echo $categoria['id']; ?>">Excluir</a></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
