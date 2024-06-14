<?php
session_start();
include 'conexao.php'; // Inclui o script de conexão

// Verifica se o usuário está logado
if(!isset($_SESSION['login'])) {
    header("location: index.php"); // Redireciona para a página de login
    exit();
}

$mensagem = $erro = "";
$tabela = "";
$bairro = "";

// Carrega os bairros com base na tabela selecionada
$bairros = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tabela'])) {
    $tabela = $_POST['tabela'];
    $query_bairros = "SELECT DISTINCT bairro FROM $tabela";
    $result_bairros = $conexao->query($query_bairros);
    while($row = $result_bairros->fetch_assoc()) {
        $bairros[] = $row['bairro'];
    }
}

// Verifica se o formulário de filtro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bairro']) && isset($_POST['tabela'])) {
    $bairro = $_POST['bairro'];
    $tabela = $_POST['tabela'];

    // Consulta no banco de dados as solicitações no bairro selecionado
    $query = "SELECT * FROM $tabela WHERE bairro='$bairro'";
    $result = $conexao->query($query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Doações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bin/img/sos2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }
        form {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            color: black; /* Alterando a cor do texto para preto */
        }
        label, select {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .table-selector {
            position: absolute;
            top: 10px;
            left: 10px;
        }
    </style>
</head>
<body>
    <a href="logout.php" class="logout-btn">Sair</a>
    
    <!-- Combo box para selecionar a tabela -->
    <div class="table-selector">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="tabela">Selecione a Tabela:</label><br>
            <select id="tabela" name="tabela" required onchange="this.form.submit()">
                <option value="">Selecione...</option>
                <option value="Donatario" <?php if($tabela == 'Donatario') echo 'selected'; ?>>Donatario</option>
                <option value="Solicitacao" <?php if($tabela == 'Solicitacao') echo 'selected'; ?>>Solicitacao</option>
            </select>
        </form>
    </div>

    <h2>Consulta de Doações por Bairro</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Inclui o valor da tabela selecionada -->
        <input type="hidden" name="tabela" value="<?php echo $tabela; ?>">
        
        <!-- Seu formulário de filtro aqui -->
        <label for="estado">Selecione o Estado:</label><br>
        <select id="estado" name="estado" required>
            <option value="ESPIRITO SANTO">ESPIRITO SANTO</option>
            <!-- Adicione mais opções de estado aqui, se necessário -->
        </select><br><br>
        <label for="cidade">Selecione a Cidade:</label><br>
        <select id="cidade" name="cidade" required>
            <option value="SERRA">SERRA</option>
            <!-- Adicione mais opções de cidade aqui, se necessário -->
        </select><br><br>
        <label for="bairro">Selecione o Bairro:</label><br>
        <select id="bairro" name="bairro" required>
            <!-- Opções de bairros devem ser carregadas do banco de dados -->
            <?php
            foreach ($bairros as $bairro_option) {
                echo "<option value='".$bairro_option."'".($bairro == $bairro_option ? ' selected' : '').">".$bairro_option."</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Filtrar">
        
        <!-- Tabela para exibir os resultados filtrados -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result) && $result->num_rows > 0) {
            echo "<h3>Resultados do Filtro:</h3>";
            echo "<table>";
            if ($tabela == "Donatario") {
                echo "<tr>";
                echo "<th>CPF</th>";
                echo "<th>Nome</th>";
                echo "<th>Contato</th>";
                echo "<th>Email</th>";
                echo "<th>Endereço</th>";
                echo "<th>Estado</th>";
                echo "<th>Cidade</th>";
                echo "<th>Bairro</th>";
                echo "<th>Número</th>";
                echo "<th>Complemento</th>";
                echo "<th>Descrição</th>";
                echo "</tr>";

                // Exibir os resultados da tabela Donatario
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['CPF']."</td>";
                    echo "<td>".$row['Nome']."</td>";
                    echo "<td>".$row['Contato']."</td>";
                    echo "<td>".$row['Email']."</td>";
                    echo "<td>".$row['Endereco']."</td>";
                    echo "<td>".$row['Estado']."</td>";
                    echo "<td>".$row['Cidade']."</td>";
                    echo "<td>".$row['bairro']."</td>";
                    echo "<td>".$row['Numero']."</td>";
                    echo "<td>".$row['Complemento']."</td>";
                    echo "<td>".$row['Descricao']."</td>";
                    echo "</tr>";
                }
            } else if ($tabela == "Solicitacao") {
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nome</th>";
                echo "<th>CPF</th>";
                echo "<th>Contato</th>";
                echo "<th>Email</th>";
                echo "<th>Endereço</th>";
                echo "<th>Estado</th>";
                echo "<th>Cidade</th>";
                echo "<th>Bairro</th>";
                echo "<th>Número</th>";
                echo "<th>Complemento</th>";
                echo "<th>Descrição</th>";
                echo "</tr>";

                // Exibir os resultados da tabela Solicitacao
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id_solicitacao']."</td>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['CPF']."</td>";
                    echo "<td>".$row['contato']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['endereco']."</td>";
                    echo "<td>".$row['estado']."</td>";
                    echo "<td>".$row['cidade']."</td>";
                    echo "<td>".$row['bairro']."</td>";
                    echo "<td>".$row['numero']."</td>";
                    echo "<td>".$row['complemento']."</td>";
                    echo "<td>".$row['descricao']."</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        ?>
    </form>

    <!-- Botão para exportar para Excel -->
    <form action="exportar_excel.php" method="post">
        <input type="hidden" name="tabela" value="<?php echo $tabela; ?>">
        <input type="hidden" name="bairro" value="<?php echo $bairro; ?>">
        <input type="submit" value="Exportar para Excel">
    </form>
</body>
</html>
