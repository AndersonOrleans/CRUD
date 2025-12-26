# cadastrar_alunos.php

```
<?php 

function limpar_texto($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

$erro = false;

if(count($_POST) > 0) {

    include('conexcao.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    if(empty($nome)) {
        $erro = "Preencha o nome";
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o e-mail";
    }

    if (!empty($nascimento)) {

        $pedacos = explode('/', $nascimento);

        if(count($pedacos) == 3) {
            $nascimento = implode ('-', array_reverse($pedacos));
        } else {
            $erro = "A data de nascimento deve seguir o padão dia/mês/ano.";
        }
    }

    if(!empty($telefone)) {

        $telefone = limpar_texto($telefone);

        if (strlen($telefone) != 11)
            $erro = "O telefone deve ser preenchido no padão (11) 98888-8888";
    }

    if($erro) {
        echo "<p><b>ERRO: $erro</b><p>";
    } 
    
    else {
        $sql_code = "INSERT INTO alunos (nome, email, telefone, nascimento, data_cadastro)
        VALUE ('$nome', '$email', '$telefone', '$nascimento', NOW())";

        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);

        if ($deu_certo) {

            echo "<p><b>Cliente cadastrado com sucesso!!!</br></p>";

            unset($_POST);
        } 
    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Alunos</title>
</head>
<body>

    <a href="/clientes.php">Voltar para a lista</a>
    
    <form method="POST" action="">
        
        <p>
            <label>Nome: </label>
            
            <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" type="text">
        </p>

        <p>
            <label>Email: </label>

            <input value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" name="email" type="text">
        </p>

        <p>
            <label>Telefone: </label>
            
            <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" placeholder="(48) 98888-8888" name="telefone" type="text">
        </p>

        <p>
            <label>Data de Nascimento: </label>
            
            <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" name="nascimento" type="text">
        </p>

        <p>
            <button type="submit">Salvar Cliente</button>
        </p>

    </form>
</body>
</html>
```






















