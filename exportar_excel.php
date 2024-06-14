<?php
include 'conexao.php'; // Inclui o script de conexão

// Verifica se o usuário está logado
session_start();
if(!isset($_SESSION['login'])) {
    header("location: index.php"); // Redireciona para a página de login
    exit();
}

// Verifica se os dados foram enviados corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tabela']) && isset($_POST['bairro'])) {
    $tabela = $_POST['tabela'];
    $bairro = $_POST['bairro'];

    // Verifica se os valores estão corretamente recebidos
    if (empty($tabela) || empty($bairro)) {
        echo "Tabela ou Bairro não definidos.";
        exit();
    }

    // Consulta para buscar os dados com base na tabela e bairro
    $query = "SELECT * FROM $tabela WHERE bairro='$bairro'";
    $result = $conexao->query($query);

    if ($result->num_rows > 0) {
        // Definimos o nome do arquivo que será exportado
        $arquivo = $tabela . '_' . $bairro . '.xls';

        // Configurações header para forçar o download
        header("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
        header("Content-Description: PHP Generated Data");

        // Inicializa a variável que irá conter o HTML da tabela
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td colspan="12">Planilha de ' . ucfirst($tabela) . ' - Bairro ' . $bairro . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';

        // Cria a tabela HTML com os dados das solicitações
        if ($tabela == "Donatario") {
            $html .= '<td><b>CPF</b></td>';
            $html .= '<td><b>Nome</b></td>';
            $html .= '<td><b>Contato</b></td>';
            $html .= '<td><b>Email</b></td>';
            $html .= '<td><b>Endereço</b></td>';
            $html .= '<td><b>Estado</b></td>';
            $html .= '<td><b>Cidade</b></td>';
            $html .= '<td><b>Bairro</b></td>';
            $html .= '<td><b>Número</b></td>';
            $html .= '<td><b>Complemento</b></td>';
            $html .= '<td><b>Descrição</b></td>';
        } else if ($tabela == "Solicitacao") {
            $html .= '<td><b>ID</b></td>';
            $html .= '<td><b>Nome</b></td>';
            $html .= '<td><b>CPF</b></td>';
            $html .= '<td><b>Contato</b></td>';
            $html .= '<td><b>Email</b></td>';
            $html .= '<td><b>Endereço</b></td>';
            $html .= '<td><b>Estado</b></td>';
            $html .= '<td><b>Cidade</b></td>';
            $html .= '<td><b>Bairro</b></td>';
            $html .= '<td><b>Número</b></td>';
            $html .= '<td><b>Complemento</b></td>';
            $html .= '<td><b>Descrição</b></td>';
        }
        $html .= '</tr>';

        // Loop para adicionar cada registro na tabela HTML
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            if ($tabela == "Donatario") {
                $html .= '<td>' . $row['CPF'] . '</td>';
                $html .= '<td>' . $row['Nome'] . '</td>';
                $html .= '<td>' . $row['Contato'] . '</td>';
                $html .= '<td>' . $row['Email'] . '</td>';
                $html .= '<td>' . $row['Endereco'] . '</td>';
                $html .= '<td>' . $row['Estado'] . '</td>';
                $html .= '<td>' . $row['Cidade'] . '</td>';
                $html .= '<td>' . $row['bairro'] . '</td>';
                $html .= '<td>' . $row['Numero'] . '</td>';
                $html .= '<td>' . $row['Complemento'] . '</td>';
                $html .= '<td>' . $row['Descricao'] . '</td>';
            } else if ($tabela == "Solicitacao") {
                $html .= '<td>' . $row['id_solicitacao'] . '</td>';
                $html .= '<td>' . $row['nome'] . '</td>';
                $html .= '<td>' . $row['CPF'] . '</td>';
                $html .= '<td>' . $row['contato'] . '</td>';
                $html .= '<td>' . $row['email'] . '</td>';
                $html .= '<td>' . $row['endereco'] . '</td>';
                $html .= '<td>' . $row['estado'] . '</td>';
                $html .= '<td>' . $row['cidade'] . '</td>';
                $html .= '<td>' . $row['bairro'] . '</td>';
                $html .= '<td>' . $row['numero'] . '</td>';
                $html .= '<td>' . $row['complemento'] . '</td>';
                $html .= '<td>' . $row['descricao'] . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Envia o conteúdo do arquivo
        echo $html;
        exit;
    } else {
        echo "Nenhum registro encontrado para o bairro selecionado.";
    }
} else {
    echo "Requisição inválida.";
}
?>
