<?php
session_start();
include 'conexao.php'; // Inclui o script de conexão

// Verifica se o usuário está logado
if(!isset($_SESSION['login'])) {
    header("location: index.php"); // Redireciona para a página de login
    exit();
}

// Verifica se o formulário foi submetido
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os valores do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $contato = $_POST['contato'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $descricao = $_POST['descricao'];

    // Obtém o timestamp atual e converte em inteiro
    $id_solicitacao = (int) time();

    // Insere os dados no banco de dados
    $query = "INSERT INTO solicitacao (id_solicitacao, nome, CPF, contato, email, endereco, estado, cidade, bairro, numero, complemento, descricao, data_hora) 
              VALUES ($id_solicitacao, '$nome', $cpf, $contato, '$email', '$endereco', '$estado', '$cidade', '$bairro', $numero, '$complemento', '$descricao', NOW())";
    
    if($conexao->query($query) === TRUE) {
        echo "Formulário enviado com sucesso!";
    } else {
        echo "Erro ao enviar o formulário: " . $conexao->error;
    }
}
?>