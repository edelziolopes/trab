<?php
session_start();

$meses = ['maio', 'junho', 'julho'];
$mesSelecionado = $_GET['mes'] ?? 'maio';
if (!in_array($mesSelecionado, $meses)) {
    $mesSelecionado = 'maio';
}

if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}
if (!isset($_SESSION['projetos'])) {
    $_SESSION['projetos'] = [];
}
foreach ($meses as $mes) {
    if (!isset($_SESSION['alunos'][$mes])) $_SESSION['alunos'][$mes] = [];
    if (!isset($_SESSION['projetos'][$mes])) $_SESSION['projetos'][$mes] = [];
}

function gerarIdUnico() {
    return uniqid();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
        if ($acao === 'adicionar_aluno') {
            $nome = trim($_POST['nome_aluno'] ?? '');
            $mes = $_POST['mes_aluno'] ?? 'maio';
            if ($nome !== '' && in_array($mes, $GLOBALS['meses'])) {
                $id = gerarIdUnico();
                $fotoUrl = null;
                if (!empty($_FILES['foto_aluno']['name'])) {
                    $extensoesPermitidas = ['jpg','jpeg','png','gif','webp'];
                    $ext = strtolower(pathinfo($_FILES['foto_aluno']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, $extensoesPermitidas)) {
                        if (!is_dir('uploads')) mkdir('uploads', 0755);
                        $novoNome = 'uploads/' . $id . '.' . $ext;
                        if (move_uploaded_file($_FILES['foto_aluno']['tmp_name'], $novoNome)) {
                            $fotoUrl = $novoNome;
                        }
                    }
                }
                if (!$fotoUrl) {
                    $url = trim($_POST['url_foto_aluno'] ?? '');
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        $fotoUrl = $url;
                    }
                }
                if (!$fotoUrl) {
                    $fotoUrl = 'https://www.svgrepo.com/show/384670/account-avatar-profile-user.svg';
                }
                $_SESSION['alunos'][$mes][$id] = [
                    'id' => $id,
                    'nome' => htmlspecialchars($nome, ENT_QUOTES),
                    'foto' => $fotoUrl,
                ];
            }
        }
        elseif ($acao === 'excluir_aluno') {
            $idExcluir = $_POST['id_aluno'] ?? '';
            $mesExcluir = $_POST['mes_aluno'] ?? 'maio';
            if (isset($_SESSION['alunos'][$mesExcluir][$idExcluir])) {
                $foto = $_SESSION['alunos'][$mesExcluir][$idExcluir]['foto'];
                if (strpos($foto, 'uploads/') === 0 && file_exists($foto)) {
                    unlink($foto);
                }
                unset($_SESSION['alunos'][$mesExcluir][$idExcluir]);
                if (isset($_SESSION['projetos'][$mesExcluir][$idExcluir])) {
                    unset($_SESSION['projetos'][$mesExcluir][$idExcluir]);
                }
            }
        }
        elseif ($acao === 'adicionar_projeto') {
            $mesProjeto = $_POST['mes_aluno'] ?? 'maio';
            $idAluno = $_POST['id_aluno'] ?? '';
            $tituloProjeto = trim($_POST['titulo_projeto'] ?? '');
            $descricaoProjeto = trim($_POST['descricao_projeto'] ?? '');
            $imagemProjetoUrl = null;

            if (!empty($_FILES['imagem_projeto']['name'])) {
                $extensoesPermitidas = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($_FILES['imagem_projeto']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $extensoesPermitidas)) {
                    if (!is_dir('uploads')) mkdir('uploads', 0755);
                    $novoNome = 'uploads/proj_' . gerarIdUnico() . '.' . $ext;
                    if (move_uploaded_file($_FILES['imagem_projeto']['tmp_name'], $novoNome)) {
                        $imagemProjetoUrl = $novoNome;
                    }
                }
            }

            if ($tituloProjeto !== '' && isset($_SESSION['alunos'][$mesProjeto][$idAluno])) {
                if (!isset($_SESSION['projetos'][$mesProjeto][$idAluno])) {
                    $_SESSION['projetos'][$mesProjeto][$idAluno] = [];
                }
                $_SESSION['projetos'][$mesProjeto][$idAluno][] = [
                    'titulo' => htmlspecialchars($tituloProjeto, ENT_QUOTES),
                    'descricao' => htmlspecialchars($descricaoProjeto, ENT_QUOTES),
                    'imagem' => $imagemProjetoUrl
                ];
            }
        }
    }
    header("Location: ".$_SERVER['PHP_SELF']."?mes=".$mesSelecionado);
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Industrial Digital - Trabalhos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  /* Variáveis de tema */
  :root {
    --bg: #f9f9f9;
    --text: #222;
    --nav-bg: #fff;
    --nav-hover: #e0e0e0;
    --card-bg: #fff;
    --card-shadow: rgba(0,0,0,0.1);
    --btn-primary-bg: #007bff;
    --btn-primary-hover: #0056b3;
  }
  [data-theme="dark"] {
    --bg: #121212;
    --text: #eee;
    --nav-bg: #1f1f1f;
    --nav-hover: #333;
    --card-bg: #1e1e1e;
    --card-shadow: rgba(255,255,255,0.1);
    --btn-primary-bg: #0d6efd;
    --btn-primary-hover: #0a58ca;
  }

  /* Reset básico */
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0; padding: 0; height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg);
    color: var(--text);
    transition: background-color 0.3s, color 0.3s;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  /* Nav meses pequeno e compacto */
  nav {
    background-color: var(--nav-bg);
    padding: 0.3rem 1rem;
    display: flex;
    gap: 0.6rem;
    justify-content: center;
    border-bottom: 1px solid var(--nav-hover);
  }
  .nav-meses a {
    color: var(--text);
    text-decoration: none;
    font-weight: 600;
    padding: 0.3rem 0.7rem;
    border-radius: 6px;
    font-size: 0.85rem;
    line-height: 1.2;
    transition: background-color 0.3s;
  }
  .nav-meses a:hover,
  .nav-meses a.active {
    background-color: var(--btn-primary-bg);
    color: #fff;
  }

  /* Container principal */
  .container-principal {
    flex: 1;
    padding: 1rem 1rem 3rem;
    max-width: 1100px;
    margin: 0 auto;
    width: 100%;
  }

  /* Título */
  h2 {
    text-align: center;
    margin-bottom: 1.5rem;
  }

  /* Botões tema e adicionar */
  .btn-tema, .btn-adicionar {
    cursor: pointer;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    padding: 0.35rem 0.75rem;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: background-color 0.3s;
  }
  .btn-tema {
    background-color: var(--btn-primary-bg);
    color: white;
  }
  .btn-tema:hover {
    background-color: var(--btn-primary-hover);
  }
  .btn-adicionar {
    background-color: #28a745;
    color: white;
    margin-left: 0.5rem;
  }
  .btn-adicionar:hover {
    background-color: #1e7e34;
  }
  .top-bar {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
    gap: 0.5rem;
  }

  /* Cards dos alunos */
  .alunos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
    gap: 1rem;
  }
  .aluno-card {
    background-color: var(--card-bg);
    box-shadow: 0 2px 6px var(--card-shadow);
    border-radius: 12px;
    padding: 0.8rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
    transition: transform 0.2s;
  }
  .aluno-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px var(--card-shadow);
  }
  .aluno-foto {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--btn-primary-bg);
  }
  .aluno-nome {
    font-weight: 700;
    font-size: 1rem;
    overflow-wrap: break-word;
  }

  /* Botões dentro dos cards alunos */
  .btn-aluno {
    font-size: 0.85rem;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    color: white;
  }
  .btn-projetos {
    background-color: var(--btn-primary-bg);
  }
  .btn-projetos:hover {
    background-color: var(--btn-primary-hover);
  }
  .btn-excluir {
    background-color: #dc3545;
    margin-left: 0.4rem;
  }
  .btn-excluir:hover {
    background-color: #b02a37;
  }

  /* Modal Projetos */
  .modal-fundo {
    position: fixed;
    inset: 0;
    background-color: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 1rem;
  }
  .modal-fundo.active {
    display: flex;
  }
  .modal-conteudo {
    background-color: var(--card-bg);
    color: var(--text);
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
    padding: 1rem 1.5rem 2rem;
    box-shadow: 0 6px 15px var(--card-shadow);
    position: relative;
  }
  .modal-conteudo h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-weight: 700;
  }
  .modal-fechar {
    position: absolute;
    top: 0.6rem;
    right: 0.6rem;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text);
    background: none;
    border: none;
  }

  /* Form projetos */
  .form-projeto {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 1rem;
  }
  .form-projeto input[type="text"],
  .form-projeto textarea {
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 0.9rem;
    resize: vertical;
  }
  .form-projeto textarea {
    min-height: 60px;
  }
  .form-projeto input[type="file"] {
    font-size: 0.85rem;
  }
  .form-projeto button {
    align-self: flex-start;
    background-color: var(--btn-primary-bg);
    border: none;
    padding: 0.4rem 0.8rem;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  .form-projeto button:hover {
    background-color: var(--btn-primary-hover);
  }

  /* Lista de projetos */
  .lista-projetos {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .projeto-item {
    border-radius: 8px;
    background-color: var(--nav-bg);
    padding: 0.8rem;
    box-shadow: 0 1px 6px var(--card-shadow);
    display: flex;
    gap: 1rem;
    align-items: center;
  }
  .projeto-item img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
    border: 1px solid #ddd;
  }
  .projeto-info {
    flex: 1;
  }
  .projeto-info h4 {
    margin: 0 0 0.3rem;
    font-weight: 700;
  }
  .projeto-info p {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text);
    opacity: 0.8;
  }

  /* Footer */
  footer {
    text-align: center;
    padding: 1rem 0;
    background-color: var(--nav-bg);
    color: var(--text);
    border-top: 1px solid var(--nav-hover);
    font-size: 0.9rem;
  }

  /* Responsividade */
  @media (max-width: 600px) {
    nav {
      flex-wrap: wrap;
    }
    .alunos-grid {
      grid-template-columns: repeat(auto-fill,minmax(140px,1fr));
    }
    .projeto-item {
      flex-direction: column;
      align-items: flex-start;
    }
    .projeto-item img {
      width: 100%;
      height: auto;
      margin-bottom: 0.5rem;
    }
  }
</style>

</head>
<body>

<header class="top-bar">
  <button class="btn-tema" id="btnTema"><i class="bi bi-moon-fill"></i> Tema</button>
  <button class="btn-adicionar" id="btnAdicionarAluno"><i class="bi bi-person-plus-fill"></i> Adicionar Aluno</button>
</header>

<nav class="nav-meses">
  <?php foreach ($meses as $mes): ?>
    <a href="?mes=<?= $mes ?>" class="<?= $mes === $mesSelecionado ? 'active' : '' ?>"><?= ucfirst($mes) ?></a>
  <?php endforeach; ?>
</nav>

<main class="container-principal">

  <h2>Alunos - <?= ucfirst($mesSelecionado) ?></h2>

  <div class="alunos-grid">
    <?php foreach ($_SESSION['alunos'][$mesSelecionado] as $aluno): ?>
      <div class="aluno-card">
        <img class="aluno-foto" src="<?= $aluno['foto'] ?>" alt="Foto de <?= $aluno['nome'] ?>" />
        <div class="aluno-nome"><?= $aluno['nome'] ?></div>
        <div>
          <button class="btn-aluno btn-projetos" data-id="<?= $aluno['id'] ?>" data-mes="<?= $mesSelecionado ?>">Projetos</button>
          <form method="post" style="display:inline-block" onsubmit="return confirm('Excluir aluno?');">
            <input type="hidden" name="acao" value="excluir_aluno" />
            <input type="hidden" name="id_aluno" value="<?= $aluno['id'] ?>" />
            <input type="hidden" name="mes_aluno" value="<?= $mesSelecionado ?>" />
            <button type="submit" class="btn-aluno btn-excluir" title="Excluir Aluno"><i class="bi bi-trash-fill"></i></button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</main>

<footer>Projeto feito por Fabio 2* sistemas</footer>

<!-- Modal Adicionar Aluno -->
<div class="modal-fundo" id="modalAdicionarAluno">
  <div class="modal-conteudo">
    <button class="modal-fechar" id="fecharAdicionarAluno" title="Fechar">&times;</button>
    <h3>Adicionar Aluno</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="acao" value="adicionar_aluno" />
      <div>
        <label for="nome_aluno">Nome do Aluno:</label><br />
        <input type="text" id="nome_aluno" name="nome_aluno" required />
      </div>
      <div>
        <label for="mes_aluno">Mês:</label><br />
        <select id="mes_aluno" name="mes_aluno" required>
          <?php foreach ($meses as $mes): ?>
            <option value="<?= $mes ?>" <?= $mes === $mesSelecionado ? 'selected' : '' ?>><?= ucfirst($mes) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Foto do Aluno (upload ou URL):</label><br />
        <input type="file" name="foto_aluno" accept="image/*" /><br />
        <input type="url" name="url_foto_aluno" placeholder="URL da imagem" style="margin-top: 0.5rem; width: 100%;" />
      </div>
      <div style="margin-top:1rem;">
        <button type="submit" class="btn-adicionar">Adicionar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Projetos -->
<div class="modal-fundo" id="modalProjetos">
  <div class="modal-conteudo">
    <button class="modal-fechar" id="fecharModalProjetos" title="Fechar">&times;</button>
    <h3 id="tituloModalProjetos">Projetos do Aluno</h3>
    <div id="listaProjetos"></div>

    <h4>Adicionar Projeto</h4>
    <form id="formAdicionarProjeto" method="post" enctype="multipart/form-data" class="form-projeto">
      <input type="hidden" name="acao" value="adicionar_projeto" />
      <input type="hidden" name="id_aluno" name="id_aluno" />
      <input type="hidden" name="mes_aluno" value="<?= $mesSelecionado ?>" />
      <input type="hidden" id="inputIdAluno" name="id_aluno" />
      <input type="hidden" id="inputMesAluno" name="mes_aluno" value="<?= $mesSelecionado ?>" />
      <input type="text" name="titulo_projeto" placeholder="Título do projeto" required />
      <textarea name="descricao_projeto" placeholder="Descrição do projeto" required></textarea>
      <input type="file" name="imagem_projeto" accept="image/*" />
      <button type="submit">Adicionar Projeto</button>
    </form>
  </div>
</div>

<script>
  // Tema claro/escuro
  const btnTema = document.getElementById('btnTema');
  const html = document.documentElement;
  btnTema.addEventListener('click', () => {
    const temaAtual = html.getAttribute('data-theme');
    if (temaAtual === 'light') {
      html.setAttribute('data-theme', 'dark');
      btnTema.innerHTML = '<i class="bi bi-sun-fill"></i> Claro';
      localStorage.setItem('tema', 'dark');
    } else {
      html.setAttribute('data-theme', 'light');
      btnTema.innerHTML = '<i class="bi bi-moon-fill"></i> Escuro';
      localStorage.setItem('tema', 'light');
    }
  });
  // Setar tema salvo no localStorage
  window.addEventListener('DOMContentLoaded', () => {
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo === 'dark') {
      html.setAttribute('data-theme', 'dark');
      btnTema.innerHTML = '<i class="bi bi-sun-fill"></i> Claro';
    } else {
      html.setAttribute('data-theme', 'light');
      btnTema.innerHTML = '<i class="bi bi-moon-fill"></i> Escuro';
    }
  });

  // Modal Adicionar Aluno
  const btnAdicionarAluno = document.getElementById('btnAdicionarAluno');
  const modalAdicionarAluno = document.getElementById('modalAdicionarAluno');
  const fecharAdicionarAluno = document.getElementById('fecharAdicionarAluno');
  btnAdicionarAluno.addEventListener('click', () => {
    modalAdicionarAluno.classList.add('active');
  });
  fecharAdicionarAluno.addEventListener('click', () => {
    modalAdicionarAluno.classList.remove('active');
  });
  window.addEventListener('click', (e) => {
    if (e.target === modalAdicionarAluno) {
      modalAdicionarAluno.classList.remove('active');
    }
  });

  // Modal Projetos
  const modalProjetos = document.getElementById('modalProjetos');
  const fecharModalProjetos = document.getElementById('fecharModalProjetos');
  const listaProjetos = document.getElementById('listaProjetos');
  const tituloModalProjetos = document.getElementById('tituloModalProjetos');
  const formAdicionarProjeto = document.getElementById('formAdicionarProjeto');
  const inputIdAluno = document.getElementById('inputIdAluno');
  const inputMesAluno = document.getElementById('inputMesAluno');

  document.querySelectorAll('.btn-projetos').forEach(btn => {
    btn.addEventListener('click', () => {
      const idAluno = btn.getAttribute('data-id');
      const mesAluno = btn.getAttribute('data-mes');
      inputIdAluno.value = idAluno;
      inputMesAluno.value = mesAluno;

      // Atualizar título do modal
      const alunoNome = btn.parentElement.parentElement.querySelector('.aluno-nome').textContent;
      tituloModalProjetos.textContent = 'Projetos de ' + alunoNome;

      // Limpar lista
      listaProjetos.innerHTML = '<em>Carregando projetos...</em>';

      // Requisição AJAX para obter projetos do aluno
      fetch('?acao=buscar_projetos&mes=' + mesAluno + '&id=' + idAluno)
        .then(res => res.json())
        .then(data => {
          if (!data || !data.projetos || data.projetos.length === 0) {
            listaProjetos.innerHTML = '<p><em>Sem projetos cadastrados.</em></p>';
          } else {
            listaProjetos.innerHTML = '';
            data.projetos.forEach(proj => {
              const div = document.createElement('div');
              div.classList.add('projeto-item');
              div.innerHTML = `
                ${proj.imagem ? `<img src="${proj.imagem}" alt="${proj.titulo}" />` : ''}
                <div class="projeto-info">
                  <h4>${proj.titulo}</h4>
                  <p>${proj.descricao}</p>
                </div>
              `;
              listaProjetos.appendChild(div);
            });
          }
          modalProjetos.classList.add('active');
        })
        .catch(() => {
          listaProjetos.innerHTML = '<p><em>Erro ao carregar projetos.</em></p>';
          modalProjetos.classList.add('active');
        });
    });
  });

  fecharModalProjetos.addEventListener('click', () => {
    modalProjetos.classList.remove('active');
  });
  window.addEventListener('click', (e) => {
    if (e.target === modalProjetos) {
      modalProjetos.classList.remove('active');
    }
  });

</script>

<?php
// Ajax simples para carregar projetos do aluno via fetch
if (isset($_GET['acao']) && $_GET['acao'] === 'buscar_projetos') {
    header('Content-Type: application/json');
    $mes = $_GET['mes'] ?? '';
    $idAluno = $_GET['id'] ?? '';
    $retorno = ['projetos' => []];
    if (isset($_SESSION['projetos'][$mes][$idAluno])) {
        $retorno['projetos'] = $_SESSION['projetos'][$mes][$idAluno];
    }
    echo json_encode($retorno);
    exit;
}
?>

</body>
</html>
