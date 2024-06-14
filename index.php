<?php
session_start();
include 'conexao.php'; // Inclui o script de conexão

// Verifica se o usuário está logado, se sim, redireciona para a página de consulta
if(isset($_SESSION['login'])) {
    header("location: consulta.php");
    exit();
}

$mensagem = $erro = "";

// Verifica se o formulário de login foi enviado
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']) && isset($_POST['password'])) {
    // Pega os valores do formulário
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Consulta no banco de dados se as credenciais estão corretas
    $query = "SELECT * FROM usuarios WHERE login='$login' AND senha='$password'";
    $result = $conexao->query($query);

    // Se encontrou um usuário com as credenciais informadas
    if($result->num_rows == 1) {
        $_SESSION['login'] = $login; // Inicia a sessão com o login
        header("location: consulta.php"); // Redireciona para a página de consulta
        exit();
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}

// Verifica se o formulário de doação foi enviado
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome']) && isset($_POST['cpf']) && isset($_POST['contato']) && isset($_POST['email']) && isset($_POST['endereco']) && isset($_POST['estado']) && isset($_POST['cidade']) && isset($_POST['bairro']) && isset($_POST['numero']) && isset($_POST['descricao'])) {

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
    $query = "INSERT INTO solicitacao (id_solicitacao, nome, CPF, contato, email, endereco, estado, cidade, bairro, numero, complemento, descricao) 
              VALUES ($id_solicitacao, '$nome', $cpf, $contato, '$email', '$endereco', '$estado', '$cidade', '$bairro', $numero, '$complemento', '$descricao')";

    if($conexao->query($query) === TRUE) {
        $mensagem = "Doação registrada com sucesso!";
    } else {
        $erro = "Erro ao registrar a doação: " . $conexao->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOE AQUI!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bin/img/sos.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .login {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .login input {
            margin-right: 10px;
            padding: 5px;
            font-size: 14px;
        }
        .login button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .login button:hover {
            background-color: #45a049;
        }
        .header {
            margin-top: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
        }
        .form-container {
            margin-top: 20px;
            width: 40%;
            background: rgba(255, 255, 255, 0.8); /* Fundo branco semi-transparente */
            padding: 20px;
            border-radius: 10px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container form label {
            margin-top: 10px;
            color: black; /* Texto em preto para contraste */
            font-size: 14px;
        }
        .form-container form input, .form-container form select, .form-container form textarea {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-container form textarea {
            resize: vertical;
            height: 80px;
        }
        .form-container form button {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-container form button:hover {
            background-color: #45a049;
        }
        .message-box {
            position: fixed;
            bottom: 10px;
            right: 10px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            display: none;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }
        .donation-button {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .donation-button button {
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
        }
        .donation-button button:hover {
            background-color: #1E88E5;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <!-- Botão de Receber Doações -->
        <div class="donation-button">
            <form method="post" action="resdoacao.php">
                <button type="submit">Receber Doações</button>
            </form>
        </div>
        <!-- Formulário de login -->
        <div class="login">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" id="login" name="login" placeholder="Usuário" required>
                <input type="password" id="password" name="password" placeholder="Senha" required>
                <button type="submit">Logar</button>
            </form>
            <?php if(isset($erro)) echo "<p>$erro</p>"; ?>
        </div>

        <div class="header">
            <h1>Doe Aqui!</h1>
        </div>

        <?php
            if(isset($mensagem)) {
                echo "<p class='message-box success'>$mensagem</p>";
            }
            if(isset($erro)) {
                echo "<p class='message-box error'>$erro</p>";
            }
            ?>

        <div class="form-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="nome">Seu Nome Completo</label>
                <input type="text" id="nome" name="nome" required>

                <label for="cpf">Seu CPF</label>
                <input type="text" id="cpf" name="cpf" required>

                <label for="contato">Contato</label>
                <input type="text" id="contato" name="contato" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" required>

                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    <option value="ESPIRITO SANTO">ESPIRITO SANTO</option>
                </select>

                <label for="cidade">Cidade</label>
                <select id="cidade" name="cidade" required>
                    <option value="SERRA">SERRA</option>
                </select>

                <label for="bairro">Bairro</label>
                <select id="bairro" name="bairro" required>
                    <optgroup label="REGIÃO DE NOVA ALMEIDA">
                        <option value="Bairro Novo">Bairro Novo</option>
                        <option value="Boa Vista">Boa Vista</option>
                        <option value="Marbela">Marbela</option>
                        <option value="Nova Almeida Centro">Nova Almeida Centro</option>
                        <option value="Parque da Gaivotas">Parque da Gaivotas</option>
                        <option value="Parque Residencial Nova Almeida">Parque Residencial Nova Almeida</option>
                        <option value="Parque Nova Fé">Parque Nova Fé</option>
                        <option value="Praiamar">Praiamar</option>
                        <option value="Reis Magos">Reis Magos</option>
                        <option value="São João">São João</option>
                        <option value="Serramar">Serramar</option>
                    </optgroup>
                    <optgroup label="REGIÃO DE JACARAIPE E MANGUINHOS">
                        <option value="Bairro das Laranjeiras">Bairro das Laranjeiras</option>
                        <option value="Balneário de Carapebus">Balneário de Carapebus</option>
                        <option value="Bicanga">Bicanga</option>
                        <option value="Castelândia">Castelândia</option>
                        <option value="Centro Industrial do Município">Centro Industrial do Município</option>
                        <option value="Cidade Continental">Cidade Continental</option>
                        <option value="Condomínio Ecológico Parque da Lagoa">Condomínio Ecológico Parque da Lagoa</option>
                        <option value="Conjunto Jacaraípe">Conjunto Jacaraípe</option>
                        <option value="Costa Dourada">Costa Dourada</option>
                        <option value="Costabela">Costabela</option>
                        <option value="Enseada de Jacaralpe">Enseada de Jacaralpe</option>
                        <option value="Estância Monazítica">Estância Monazítica</option>
                        <option value="Feu Rosa">Feu Rosa</option>
                        <option value="Jardim Atlântico">Jardim Atlântico</option>
                        <option value="Lagoa de Jacaraípe">Lagoa de Jacaraípe</option>
                        <option value="Manguinhos">Manguinhos</option>
                        <option value="Ourimar">Ourimar</option>
                        <option value="Parque Jacaraípe">Parque Jacaraípe</option>
                        <option value="Portal de Jacaraípe">Portal de Jacaraípe</option>
                        <option value="Praia de Capuba">Praia de Capuba</option>
                        <option value="Praia de Carapebus">Praia de Carapebus</option>
                        <option value="Residencial Jacaraípe">Residencial Jacaraípe</option>
                        <option value="São Francisco">São Francisco</option>
                        <option value="São Patrício">São Patrício</option>
                        <option value="São Pedro">São Pedro</option>
                        <option value="Sitio Irema">Sitio Irema</option>
                        <option value="Vila Nova de Colares">Vila Nova de Colares</option>
                    </optgroup>
                    <optgroup label="REGIÃO DE CARAPINA">
                        <option value="André Carloni">André Carloni</option>
                        <option value="Bairro de Fátima">Bairro de Fátima</option>
                        <option value="Boa Vista">Boa Vista</option>
                        <option value="Carapina Grande">Carapina Grande</option>
                        <option value="Conjunto Carapina I">Conjunto Carapina I</option>
                        <option value="Eurico Salles">Eurico Salles</option>
                        <option value="Hélio Ferraz">Hélio Ferraz</option>
                        <option value="Jardim Carapina">Jardim Carapina</option>
                        <option value="Manoel Plaza">Manoel Plaza</option>
                        <option value="Rosário de Fátima">Rosário de Fátima</option>
                        <option value="TIMS">TIMS</option>
                    </optgroup>
                    <optgroup label="REGIÃO DE ANCHIETA">
                        <option value="Cantinho do Céu">Cantinho do Céu</option>
                        <option value="Central Carapina">Central Carapina</option>
                        <option value="Diamantina">Diamantina</option>
                        <option value="Jardim Tropical">Jardim Tropical</option>
                        <option value="José de Anchieta">José de Anchieta</option>
                        <option value="José de Anchieta II">José de Anchieta II</option>
                        <option value="José de Anchieta III">José de Anchieta III</option>
                        <option value="Laranjeiras Velha">Laranjeiras Velha</option>
                        <option value="Solar de Anchieta">Solar de Anchieta</option>
                        <option value="Taquara I">Taquara I</option>
                        <option value="Taquara II">Taquara II</option>
                    </optgroup>
                    <optgroup label="REGIÃO DE LARANJEIRAS">
                        <option value="Alterozas">Alterozas</option>
                        <option value="Camará">Camará</option>
                        <option value="Chacará Parreiral">Chacará Parreiral</option>
                        <option value="Civit II">Civit II</option>
                        <option value="Colinas de Laranjeiras">Colinas de Laranjeiras</option>
                        <option value="Guaraciaba">Guaraciaba</option>
                        <option value="Jardim Limoeiro">Jardim Limoeiro</option>
                        <option value="Morada de Laranjeiras">Morada de Laranjeiras</option>
                        <option value="Nova Zelândia">Nova Zelândia</option>
                        <option value="Novo Horizonte">Novo Horizonte</option>
                        <option value="Parque Residencial Laranjeiras">Parque Residencial Laranjeiras</option>
                        <option value="Planalto de Carapina">Planalto de Carapina</option>
                        <option value="Santa Luzia">Santa Luzia</option>
                        <option value="São Diogo I">São Diogo I</option>
                        <option value="São Diogo II">São Diogo II</option>
                        <option value="São Geraldo">São Geraldo</option>
                        <option value="Valparaiso">Valparaiso</option>
                    </optgroup>
                    <optgroup label="REGIÃO DO CIVIT">
                        <option value="Barcelona">Barcelona</option>
                        <option value="Barro Branco">Barro Branco</option>
                        <option value="Cidade Pomar">Cidade Pomar</option>
                        <option value="Civit I">Civit I</option>
                        <option value="Eldorado">Eldorado</option>
                        <option value="Maringá">Maringá</option>
                        <option value="Mata da Serra">Mata da Serra</option>
                        <option value="Nova Carapina I">Nova Carapina I</option>
                        <option value="Nova Carapina II">Nova Carapina II</option>
                        <option value="Novo Porto Canoa">Novo Porto Canoa</option>
                        <option value="Parque Residencial Mestre Álvaro">Parque Residencial Mestre Álvaro</option>
                        <option value="Parque Residencial Tubarão">Parque Residencial Tubarão</option>
                        <option value="Pitanga">Pitanga</option>
                        <option value="Planície da Serra">Planície da Serra</option>
                        <option value="Porto Canoa">Porto Canoa</option>
                        <option value="Serra Dourada I">Serra Dourada I</option>
                        <option value="Serra Dourada II">Serra Dourada II</option>
                        <option value="Serra Dourada III">Serra Dourada III</option>
                    </optgroup>
                    <optgroup label="REGIÃO DE SERRA SEDE">
                        <option value="Belvedere">Belvedere</option>
                        <option value="Caçaroca">Caçaroca</option>
                        <option value="Campinho da Serra 1">Campinho da Serra 1</option>
                        <option value="Campinho da Serra II">Campinho da Serra II</option>
                        <option value="Centro da Serra">Centro da Serra</option>
                        <option value="Cidade Nova da Serra">Cidade Nova da Serra</option>
                        <option value="Colina da Serra">Colina da Serra</option>
                        <option value="Divinopólis">Divinopólis</option>
                        <option value="Fazenda Cascata">Fazenda Cascata</option>
                        <option value="Jardim Bela Vista">Jardim Bela Vista</option>
                        <option value="Jardim da Serra">Jardim da Serra</option>
                        <option value="Jardim Guanabara">Jardim Guanabara</option>
                        <option value="Jardim Primavera">Jardim Primavera</option>
                        <option value="Nossa Senhora da Conceição">Nossa Senhora da Conceição</option>
                        <option value="Planalto Serrano">Planalto Serrano</option>
                        <option value="Santo Antônio">Santo Antônio</option>
                        <option value="São Domingos">São Domingos</option>
                        <option value="São Judas Tadeu">São Judas Tadeu</option>
                        <option value="São Lourenço">São Lourenço</option>
                        <option value="São Marcos">São Marcos</option>
                        <option value="Serra Centro">Serra Centro</option>
                        <option value="Vila Maria Níobe">Vila Maria Níobe</option>
                        <option value="Vista da Serra I">Vista da Serra I</option>
                        <option value="Vista da Serra II">Vista da Serra II</option>
                    </optgroup>
                </select>

                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" required>

                <label for="complemento">Complemento</label>
                <input type="text" id="complemento" name="complemento">

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" maxlength="500" required></textarea>

                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>

    <script>
        // Script para exibir a mensagem de sucesso ou erro
        const messageBox = document.querySelector('.message-box');
        if (messageBox) {
            messageBox.style.display = 'block';
            setTimeout(() => {
                messageBox.style.display = 'none';
            }, 5000); // Esconde a mensagem após 5 segundos
        }
    </script>
            </form>
        </div>
    </div>

</body>
</html>