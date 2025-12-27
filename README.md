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

# alunos.php

```
<?php 

include('conexcao.php'); 

$sql_alunos = "SELECT * FROM Alunos";

$query_alunos = $mysqli->query($sql_alunos) or die($mysqli->error);

$num_alunos = $query_alunos->num_rows;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
</head>
<body>

    <h1>Lista de alunos</h1>
    
    <p>Estes são os alunos cadastrados no seu sistemas: </p>
   
    <table border="1" cellpading="10">
        
        <thead>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Nascimento</th>
            <th>Data de Cadastro</th>
            <th>Açoes</th>
        </thead>

        <tbody>
           
            <?php 
            
            if($num_alunos == 0) { 
            ?>
                <tr>
                    <td colspan="7">
                        Nenhum aluno foi cadastrado
                    </td>
                </tr>
            <?php 
            } else {

                while($alunos = $query_alunos->fetch_assoc()) {

                    $telefone = "Não informado";

                    if(!empty($alunos['telefone'])) {

                        $telefone = formatar_telefone($alunos['telefone']);
                    }

                    $nascimento = "Não informado";

                    if(!empty($alunos['nascimento'])) {

                        $nascimento = formatar_data($alunos['nascimento']);
                    }

                    $data_cadastro = date("d/m/Y H:i", strtotime($alunos['data_cadastro']));
            ?>
                    <tr>
                        <td><?php echo $alunos['id']; ?></td>
                        <td><?php echo $alunos['nome']; ?></td>
                        <td><?php echo $alunos['email']; ?></td>
                        <td><?php echo $telefone; ?></td>
                        <td><?php echo $nascimento; ?></td>
                        <td><?php echo $data_cadastro; ?></td>
                        <td>
                            <a href="editar_alunos.php?id=<?php echo $alunos['id']; ?>">
                                Editar
                            </a>
                            
                            <a href="deletar_alunos.php?id=<?php echo $alunos['id']; ?>">
                                Deletar
                            </a>
                            
                        </td>
                    </tr>
            <?php 
                    } 
                } 
            ?>
        </tbody>

    </table>

</body>
</html>
```

# conexcao.php

```
<?php 

$host = "localhost";
$db = "trabalho"; 
$user = "root";
$pass = "";

$mysqli = new mysqli($host, $user, $pass, $db); 

if ($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados");
}

function formatar_data($data) {
    return implode('/', array_reverse(explode('-', $data)));
}

function formatar_telefone($telefone) {
        $ddd = substr($telefone, 0, 2);
        $parte1 = substr($telefone, 2, 5);
        $parte2 = substr($telefone, 7);
        return "($ddd) $parte1-$parte2";
}

?>
```

# deletar_alunos.php

```
<?php 

if (isset($_POST['confirmar'])) {

    include("conexcao.php"); 
    
    $id = intval($_GET['id']);
   
    $sql_code = "DELETE FROM alunos WHERE id = '$id'";
    
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    if ($sql_query) { ?>
        
        <h1>Aluno deletado com sucesso!</h1>

        <p><a href="alunos.php">Clique aqui</a> para voltar para a lista de alunos.</p>
        
        <?php

        die();
    } 
}

?>

<!--------------------------------------------------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
</head>
<body>

    <h1>Tem certeza que deseja deletar este aluno(a)?</h1>

    <form action="" method="post">
        
        <a style="margin-right: 40px;" href="alunos.php">Não</a>
        
        <button name="confirmar" value="1" type="submit">Sim</button>
    </form>
    
</body>
</html>
```

# editar_alunos.php

```
<?php 

include('conexcao.php');

$id = intval($_GET['id']);

function limpar_texto($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

$erro = false;

if (count($_POST) > 0) {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    if (empty($nome)) {
        $erro = "Preencha o nome";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o e-mail";
    }

    if (!empty($nascimento)) {

        $pedacos = explode('/', $nascimento);

        if (count($pedacos) == 3) {

            $nascimento = implode('-', array_reverse($pedacos));
        } 
        
        else {
            $erro = "A data de nascimento deve seguir o padrão dia/mês/ano.";
        }

    }

    if (!empty($telefone)) {

        $telefone = limpar_texto($telefone);

        if (strlen($telefone) != 11)
            $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
    }

    if ($erro) {
        echo "<p><b>ERRO: $erro</b><p>";
    } 
    
    else {

        $sql_code = "UPDATE alunos SET nome = '$nome', email = '$email', telefone = '$telefone', nascimento = '$nascimento' WHERE id = '$id'";

        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);

        if ($deu_certo) {
            echo "<p><b>Alunos atualizado com sucesso!!!</b></p>";

            unset($_POST);
        } 
    }
}


$sql_alunos = "SELECT * FROM alunos WHERE id = '$id'";

$query_alunos = $mysqli->query($sql_alunos) or die($mysqli->error);

$alunos = $query_alunos->fetch_assoc();

?>

<!---------------------------------------------------------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Alunos</title>
</head>
<body>

    <a href="/alunos.php">Voltar para a lista</a>

    <form method="POST" action="">

        <p>
            <label>Nome: </label>
            <input value="<?php echo $alunos['nome']; ?>" name="nome" type="text">
        </p>

        <p>
            <label>Email: </label>
            <input value="<?php echo $alunos['email']; ?>" name="email" type="text">
        </p>

        <p>
            <label>Telefone: </label>
            <input value="<?php if (!empty($alunos['telefone'])) echo formatar_telefone($alunos['telefone']); ?>" 
                placeholder="(48) 98888-8888" 
                name="telefone" 
                type="text">
        </p>

        <p>
            <label>Data de Nascimento: </label>
            <input value="<?php if (!empty($alunos['telefone'])) echo formatar_data($alunos['nascimento']); ?>" 
                name="nascimento" 
                type="text">
        </p>

        <p>
            <button type="submit">Salvar Alunos</button>
        </p>

    </form>

</body>
</html>
```
























