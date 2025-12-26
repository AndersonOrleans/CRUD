<?php 

// O include é usado para chamar o arquivo conexcao.php e reutilizar o código de conexão com o banco de dados:
include('conexcao.php');

//-------------------------------------------------------------------------------------------------------------------------------//

/* 
Converte o ID recebido pela URL para inteiro, para ser usado com segurança nas consultas ao banco de dados:
Pega o id do cliente pela URL (GET) e converte para inteiro:
*/
$id = intval($_GET['id']);

//-------------------------------------------------------------------------------------------------------------------------------//

// função usada para remover tudo que não for número de uma string:
function limpar_texto($str) {
    // aqui remove letras, espaços e símbolos, deixando apenas números:
    return preg_replace("/[^0-9]/", "", $str);
}

//-------------------------------------------------------------------------------------------------------------------------------//

// variável que controla se existe algum erro no formulário:
$erro = false;

//-------------------------------------------------------------------------------------------------------------------------------//

// verifica se o formulário foi enviado (se existe POST):
if (count($_POST) > 0) {

    // recebe os dados enviados pelo formulário:
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    // verifica se o campo nome está vazio
    if (empty($nome)) {
        $erro = "Preencha o nome";
    }

    // verifica se o e-mail está vazio ou é inválido
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o e-mail";
    }

    // verifica se a data de nascimento foi preenchida
    if (!empty($nascimento)) {

        // separa a data usando a barra (dia/mês/ano)
        $pedacos = explode('/', $nascimento);

        // verifica se a data possui dia, mês e ano
        if (count($pedacos) == 3) {

            // inverte a data para o padrão do banco (ano-mês-dia)
            $nascimento = implode('-', array_reverse($pedacos));
        } 
        
        // define erro caso o formato esteja incorreto
        else {
            $erro = "A data de nascimento deve seguir o padrão dia/mês/ano.";
        }

    }

    // verifica se o telefone foi preenchido
    if (!empty($telefone)) {

        // remove caracteres que não são números
        $telefone = limpar_texto($telefone);

        // verifica se o telefone possui 11 dígitos
        if (strlen($telefone) != 11)
            $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
    }

    // se existir algum erro, exibe a mensagem
    if ($erro) {
        echo "<p><b>ERRO: $erro</b><p>";
    } 
    
    // se não houver erro, atualiza os dados no banco:
    else {

        // monta o comando SQL para atualizar os dados do cliente:
        $sql_code = "UPDATE clientes SET nome = '$nome', email = '$email', telefone = '$telefone', nascimento = '$nascimento' WHERE id = '$id'";

        // executa o comando SQL no banco de dados
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);

        // verifica se a atualização foi feita com sucesso
        if ($deu_certo) {
            echo "<p><b>Cliente atualizado com sucesso!!!</b></p>";

            // limpa os dados do formulário após atualizar
            unset($_POST);
        } 
    }
}

// ------------------------------------------------------------------------------------------------------------------------------------------------//

// monta o comando SQL para buscar os dados do cliente pelo id
$sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";

// executa a consulta no banco de dados
$query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);

// transforma o resultado da consulta em um array associativo
$cliente = $query_cliente->fetch_assoc();

?>

<!---------------------------------------------------------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<!-- define que o documento usa HTML5 -->
<html lang="pt-br">
<head>
    <!-- define o conjunto de caracteres como UTF-8 -->
    <meta charset="UTF-8">

    <!-- ajusta a visualização para celulares e tablets -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- título que aparece na aba do navegador -->
    <title>Cadastrar Cliente</title>
</head>
<body>

    <!-- link para voltar para a página de listagem de clientes -->
    <a href="/clientes.php">Voltar para a lista</a>

    <!-- formulário que envia os dados via método POST -->
    <form method="POST" action="">

        <p>
            <!-- rótulo do campo nome -->
            <label>Nome: </label>

            <!-- campo de texto preenchido com o nome do cliente vindo do banco -->
            <input value="<?php echo $cliente['nome']; ?>" name="nome" type="text">
        </p>

        <p>
            <!-- rótulo do campo email -->
            <label>Email: </label>

            <!-- campo de texto preenchido com o email do cliente -->
            <input value="<?php echo $cliente['email']; ?>" name="email" type="text">
        </p>

        <p>
            <!-- rótulo do campo telefone -->
            <label>Telefone: </label>

            <!-- campo de texto preenchido com o telefone formatado, se existir -->
            <input 
                value="<?php if (!empty($cliente['telefone'])) echo formatar_telefone($cliente['telefone']); ?>" 
                placeholder="(48) 98888-8888" 
                name="telefone" 
                type="text">
        </p>

        <p>
            <!-- rótulo do campo data de nascimento -->
            <label>Data de Nascimento: </label>

            <!-- campo de texto preenchido com a data formatada, se existir -->
            <input 
                value="<?php if (!empty($cliente['telefone'])) echo formatar_data($cliente['nascimento']); ?>" 
                name="nascimento" 
                type="text">
        </p>

        <p>
            <!-- botão que envia o formulário para salvar as alterações -->
            <button type="submit">Salvar Cliente</button>
        </p>

    </form>

</body>
</html>



