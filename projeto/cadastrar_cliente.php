<?php 

// Função responsável por limpar um texto, deixando apenas números
function limpar_texto($str) {
    // Remove tudo que não for número (0 a 9) usando expressão regular
    return preg_replace("/[^0-9]/", "", $str);
}

// Variável que controla se existe algum erro no formulário
$erro = false;

// Verifica se o formulário foi enviado (se existe conteúdo no $_POST)
if(count($_POST) > 0) {

    // Inclui o arquivo de conexão com o banco de dados
    include('conexcao.php');

    // Recebe os dados enviados pelo formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    // Verifica se o campo nome está vazio
    if(empty($nome)) {
        // Define uma mensagem de erro caso o nome não tenha sido preenchido
        $erro = "Preencha o nome";
    }

    // Verifica se o e-mail está vazio ou se não é um e-mail válido
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Define uma mensagem de erro para e-mail inválido ou vazio
        $erro = "Preencha o e-mail";
    }

    // Verifica se o campo nascimento foi preenchido
    if (!empty($nascimento)) {

        // Divide a data usando a barra "/" como separador
        $pedacos = explode('/', $nascimento);

        // Verifica se a data possui dia, mês e ano
        if(count($pedacos) == 3) {

            // Inverte a ordem da data para o formato ano-mês-dia
            // Isso é necessário para o padrão do banco de dados
            $nascimento = implode ('-', array_reverse($pedacos));

        } else {
            // Define erro caso a data não esteja no formato correto
            $erro = "A data de nascimento deve seguir o padão dia/mês/ano.";
        }
    }

    // Verifica se o telefone foi preenchido
    if(!empty($telefone)) {

        // Remove caracteres como parênteses, espaço e hífen
        $telefone = limpar_texto($telefone);

        // Verifica se o telefone possui exatamente 11 números
        if (strlen($telefone) != 11)
            // Define erro se o telefone não estiver no padrão esperado
            $erro = "O telefone deve ser preenchido no padão (11) 98888-8888";
    }

    // Verifica se houve algum erro durante as validações
    if($erro) {

        // Exibe a mensagem de erro na tela
        echo "<p><b>ERRO: $erro</b><p>";
    } 
    
    // Caso não exista erro, os dados serão salvos no banco
    else {

        // Comando SQL para inserir os dados do cliente no banco de dados
        // NOW() insere automaticamente a data e hora atual do cadastro
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data_cadastro)
        VALUE ('$nome', '$email', '$telefone', '$nascimento', NOW())";

        // Executa o comando SQL no banco de dados
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);

        // Verifica se o cadastro foi realizado com sucesso
        if ($deu_certo) {

            // Exibe mensagem de sucesso
            echo "<p><b>Cliente cadastrado com sucesso!!!</br></p>";

            // Limpa os dados do formulário após o envio
            unset($_POST); // Vai limpar o formulário. 
        } 
    }

}

?>

<!---------------------------------------------------------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<!-- Define o tipo do documento como HTML5 -->

<html lang="pt-br">
<!-- Define que o idioma da página é português do Brasil -->

<head>
    <meta charset="UTF-8">
    <!-- Define o padrão de caracteres para aceitar acentos e caracteres especiais -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Faz a página se adaptar corretamente a celulares e tablets -->

    <title>Cadastrar Cliente</title>
    <!-- Título da página que aparece na aba do navegador -->
</head>

<body>

    <a href="/clientes.php">Voltar para a lista</a>
    <!-- Link para voltar para a página que lista os clientes cadastrados -->

    <form method="POST" action="">
        <!-- Formulário que envia os dados via método POST -->
        <!-- action vazio significa que o formulário será enviado para a própria página -->

        <p>
            <label>Nome: </label>
            <!-- Rótulo do campo nome -->

            <input 
                value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" 
                name="nome" 
                type="text">
            <!-- Campo de texto para o nome do cliente -->
            <!-- Se o formulário já foi enviado e deu erro, mantém o valor digitado -->
        </p>

        <p>
            <label>Email: </label>
            <!-- Rótulo do campo e-mail -->

            <input 
                value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" 
                name="email" 
                type="text">
            <!-- Campo de texto para o e-mail -->
            <!-- Reaproveita o valor digitado caso o formulário tenha erro -->
        </p>

        <p>
            <label>Telefone: </label>
            <!-- Rótulo do campo telefone -->

            <input 
                value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" 
                placeholder="(48) 98888-8888" 
                name="telefone" 
                type="text">
            <!-- Campo para telefone -->
            <!-- placeholder mostra um exemplo do formato esperado -->
            <!-- value mantém o telefone digitado após erro -->
        </p>

        <p>
            <label>Data de Nascimento: </label>
            <!-- Rótulo do campo data de nascimento -->

            <input 
                value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" 
                name="nascimento" 
                type="text">
            <!-- Campo para data de nascimento -->
            <!-- Mantém a data digitada caso ocorra erro no envio -->
        </p>

        <p>
            <button type="submit">Salvar Cliente</button>
            <!-- Botão que envia o formulário -->
        </p>

    </form>
    
</body>
</html>
