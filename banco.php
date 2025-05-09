<?php
function conectar() {
    $mysqli = new mysqli(
        "localhost", "root", "", "trabalho"
    );    
    if ($mysqli->connect_error) {
        die("Falha: ".$mysqli->connect_error);
    }    
    return $mysqli;
}

function inserirIngrediente($nome, $tipo, $imagem) {
    $mysqli = conectar();
    $stmt = $mysqli->prepare("
        INSERT INTO tb_ingredientes 
        (nome, tipo, imagem) 
        VALUES (?, ?)
    ");
    $stmt->bind_param("sss", $nome, $tipo, $imagem);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
function excluirIngrediente($id) {
    $mysqli = conectar();
    $stmt = $mysqli->prepare("
    DELETE FROM tb_ingredientes WHERE id = ?");
    $stmt->bind_param("i", $id);    
    $stmt->execute();   
    $stmt->close();
    $mysqli->close();
}
function listarIngrediente() {
    $mysqli = conectar();
    $sql = "SELECT id, nome, tipo, imagem 
    FROM tb_ingredientes";
    $result = $mysqli->query($sql);
    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    $mysqli->close();
    return $categorias;
}

?>