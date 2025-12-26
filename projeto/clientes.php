<?php 

// Inclui o arquivo de conexão com o banco de dados
include('conexcao.php'); 

// Comando SQL que busca todos os registros da tabela Clientes no banco de dados
$sql_clientes = "SELECT * FROM Clientes";

// Executa o comando SQL armazenado em $sql_clientes
// Caso ocorra algum erro na consulta, o programa é interrompido e exibe o erro
$query_clientes = $mysqli->query($sql_clientes) or die($mysqli->error);

// Obtém a quantidade total de clientes retornados pela consulta
$num_clientes = $query_clientes->num_rows;

?>

<!DOCTYPE html>
<!-- Define o documento como HTML5 -->

<html lang="pt-br">
<!-- Define o idioma da página como português do Brasil -->

<head>
    <meta charset="UTF-8">
    <!-- Permite acentuação e caracteres especiais -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ajusta a visualização para dispositivos móveis -->

    <title>Lista de Clientes</title>
    <!-- Título da página -->
</head>

<body>

    <h1>Lista de clientes</h1>
    <!-- Título principal da página -->

    <p>Estes são os clientes cadastrados no seu sistemas: </p>
    <!-- Texto explicativo da página -->

    <table border="1" cellpading="10">
        <!-- Cria a tabela para exibir os clientes -->
        <!-- border define a borda da tabela -->
        <!-- cellpading define o espaço interno das células -->

        <thead>
            <!-- Cabeçalho da tabela -->

            <th>ID</th>
            <!-- Coluna do identificador do cliente -->

            <th>Nome</th>
            <!-- Coluna do nome do cliente -->

            <th>E-mail</th>
            <!-- Coluna do e-mail -->

            <th>Telefone</th>
            <!-- Coluna do telefone -->

            <th>Nascimento</th>
            <!-- Coluna da data de nascimento -->

            <th>Data de Cadastro</th>
            <!-- Coluna da data em que o cliente foi cadastrado -->

            <th>Açoes</th>
            <!-- Coluna com as ações disponíveis -->
        </thead>

        <tbody>
            <!-- Corpo da tabela -->

            <?php 
            // Verifica se não existe nenhum cliente cadastrado
            if($num_clientes == 0) { 
            ?>
                <tr>
                    <!-- Linha da tabela -->
                    <td colspan="7">
                        <!-- colspan="7" faz a célula ocupar todas as colunas -->
                        Nenhum cliente foi cadastrado
                    </td>
                </tr>
            <?php 
            } else {

                // Percorre todos os clientes retornados do banco de dados
                while($cliente = $query_clientes->fetch_assoc()) {

                    // Define um valor padrão para o telefone
                    $telefone = "Não informado";

                    // Verifica se o telefone está preenchido no banco
                    if(!empty($cliente['telefone'])) {

                        // Formata o telefone para apresentação ao usuário
                        $telefone = formatar_telefone($cliente['telefone']);
                    }
                   
                    // Define um valor padrão para a data de nascimento
                    $nascimento = "Não informado";

                    // Verifica se a data de nascimento está preenchida
                    if(!empty($cliente['nascimento'])) {

                        // Formata a data para o padrão brasileiro
                        $nascimento = formatar_data($cliente['nascimento']);
                    }

                    // Converte a data de cadastro para o formato brasileiro com hora
                    $data_cadastro = date("d/m/Y H:i", strtotime($cliente['data_cadastro']));
            ?>
                    <tr>
                        <!-- Linha com os dados do cliente -->

                        <td><?php echo $cliente['id']; ?></td>
                        <!-- Exibe o ID do cliente -->

                        <td><?php echo $cliente['nome']; ?></td>
                        <!-- Exibe o nome do cliente -->

                        <td><?php echo $cliente['email']; ?></td>
                        <!-- Exibe o e-mail do cliente -->

                        <td><?php echo $telefone; ?></td>
                        <!-- Exibe o telefone formatado ou "Não informado" -->

                        <td><?php echo $nascimento; ?></td>
                        <!-- Exibe a data de nascimento formatada -->

                        <td><?php echo $data_cadastro; ?></td>
                        <!-- Exibe a data de cadastro formatada -->

                        <td>
                            <!-- Coluna com ações -->

                            <a href="editar_cliente.php?id=<?php echo $cliente['id']; ?>">
                                Editar
                            </a>
                            <!-- Link para editar o cliente -->

                            <a href="deletar_cliente.php?id=<?php echo $cliente['id']; ?>">
                                Deletar
                            </a>
                            <!-- Link para excluir o cliente -->
                        </td>
                    </tr>
            <?php 
                    // Fecha o while
                    } 
                } 
            ?>
        </tbody>

    </table>

</body>
</html>
