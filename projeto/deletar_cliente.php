<?php 

/* isset() verifica se a variável foi criada e contém algum valor, evitando erros ao acessar dados que ainda não existem.*/
if (isset($_POST['confirmar'])) {

/* 
Verifica se o campo confirmar foi enviado pelo método POST. 
Se existir, a condição é true e o código dentro do if é executado.
*/ 

    // Se a parte do método POST é TRUE, então vamos para a segunda parte:

    include("conexcao.php"); 
    // O include é usado para chamar o arquivo conexcao.php e reutilizar o código de conexão com o banco de dados.

    $id = intval($_GET['id']);
    /* Converte o ID recebido pela URL para inteiro, para ser usado com segurança nas consultas ao banco de dados.
       Pega o id do cliente pela URL (GET) e converte para inteiro
    */

    $sql_code = "DELETE FROM clientes WHERE id = '$id'";
    // $sql_code é uma variável que armazena um comando SQL, como "DELETE FROM clientes WHERE id = '$id'", que será executado no banco de dados.


    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    if ($sql_query) { ?>
        
        <h1>Cliente deletado com sucesso!</h1>

        <p><a href="clientes.php">Clique aqui</a> para voltar para a lista de clientes.</p>
        
        <?php

        die();

        // 1° O PHP lê o comando SQL que está guardado na variável $sql_code, que é esse o comando: "DELETE FROM clientes WHERE id = '$id'".
        // 2° O objeto $mysqli usa o método query() para enviar esse comando SQL ao banco de dados.
        // 3° O banco de dados tenta executar o comando SQL: "DELETE FROM clientes WHERE id = '$id'", (no seu caso, um DELETE).
        /* 4° Se der certo:
                O resultado da execução é armazenado na variável $sql_query.
              Se der erro:
                O código or die($mysqli->error) é executado.
        */

    } 
}

?>

<!--------------------------------------------------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    
    <!-- define o ajuste da página para dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- título exibido na aba do navegador -->
    <title>Deletar Cliente</title>
    
</head>
<body>

    <!-- pergunta de confirmação antes de deletar o cliente -->
    <h1>Tem certeza que deseja deletar este cliente?</h1>

    <!-- formulário usado para confirmar a exclusão -->
    <form action="" method="post">
        
        <!-- link para cancelar e voltar à lista de clientes -->
        <a style="margin-right: 40px;" href="clientes.php">Não</a>
        
        <!-- botão que envia o formulário confirmando a exclusão -->
        <button name="confirmar" value="1" type="submit">Sim</button>
    </form>
    
</body>
</html>
