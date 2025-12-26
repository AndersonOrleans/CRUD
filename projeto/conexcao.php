<?php 

// Define o endereço do servidor do banco de dados; neste caso, estamos no localhost, que fica no próprio computador:
$host = "localhost";

// ----------------------------------------------------------------------------------------------------------------------------// 

// Define o nome do banco de dados que será utilizado:
$db = "crud_clientes"; // Neste caso o nome do meu banco de dados que foi criado no PHPMyAdmin se chama: crud_clientes.

// ----------------------------------------------------------------------------------------------------------------------------// 

// Define o usuário de acesso ao banco de dados que é root conforme está pronto no PHPMyAdmin inicial: 
$user = "root";

// ----------------------------------------------------------------------------------------------------------------------------// 

// Define a senha do usuário do banco de dados, no caso não temos senha então fica em branco: 
$pass = "";

// ----------------------------------------------------------------------------------------------------------------------------// 

/* 
EXPLICAÇÃO: 
// Cria a conexão com o banco de dados usando a classe mysqli: 
*/

$mysqli = new mysqli($host, $user, $pass, $db); 
/*
 - $host: É o nome do meu banco de dados.
 - $user: Define o usuário de acesso ao banco de dados que é root conforme está pronto no PHPMyAdmin inicial: 
 - $pass: Neste campo vai chamar a senha do banco de dados que não temos senha. 
 - $db: É o nome do banco de dados que eu criei no PHPMyAdmin. 
*/

// ----------------------------------------------------------------------------------------------------------------------------// 

// Verifica se ocorreu algum erro ao tentar conectar com o banco de dados: 
if ($mysqli->connect_errno) {

    // Interrompe a execução do sistema caso a conexão falhe
    die("Falha na conexão com o banco de dados");
}

/*

EXPLICAÇÃO: 

Essa condição verifica se ocorreu algum erro na conexão com o banco de dados MySQL usando o objeto $mysqli.

Explicando por partes:

$mysqli
É o objeto que representa a conexão com o banco de dados MySQL.

connect_errno
É uma propriedade do $mysqli que guarda o código do erro de conexão.
Se a conexão deu certo, esse valor é 0.
Se a conexão falhou, ele contém um número diferente de zero (o código do erro).

if ($mysqli->connect_errno)
Em PHP, qualquer valor diferente de 0 é considerado true.
Então, essa condição significa:
"Se existir um erro na conexão com o banco de dados"
*/


// ------------------------------------------------------------------------------------------------------------------------------------// 

function formatar_data($data) {
    return implode('/', array_reverse(explode('-', $data)));
}

/*

EXPLICAÇÃO:

– O que essa função faz:

Essa função recebe uma data no formato padrão do banco de dados (AAAA-MM-DD)  
e retorna essa mesma data no formato brasileiro (DD/MM/AAAA).

– function formatar_data($data) {  
Cria uma função chamada formatar_data, que recebe como parâmetro uma data armazenada na variável $data.

– explode('-', $data)  
Divide a string da data em partes, usando o caractere - como separador.

Exemplo:  
$data = "2024-12-24";

Resultado do explode:  
["2024", "12", "24"]

– array_reverse(...)  
Inverte a ordem dos elementos do array, colocando o dia primeiro, depois o mês e por último o ano.

Resultado:  
["24", "12", "2024"]

– implode('/', ...)  
Junta os elementos do array em uma única string, utilizando / como separador.

Resultado final:  
"24/12/2024"

*/

// ------------------------------------------------------------------------------------------------------------------------------------// 

/*

EXPLICAÇÃO:

– O que essa função faz:

Essa função recebe um número de telefone sem formatação (apenas números)
e retorna esse telefone no padrão brasileiro: (DD) 99999-9999.

– function formatar_telefone($telefone) {  
Cria uma função chamada formatar_telefone, que recebe como parâmetro
uma string contendo o número do telefone.

– substr($telefone, 0, 2)  
Extrai os dois primeiros caracteres da string, que correspondem ao DDD.

Exemplo:

$telefone = "48991234567";

Resultado:

$ddd = "48"

– substr($telefone, 2, 5)  
Extrai cinco caracteres a partir da posição 2 da string,
que correspondem à primeira parte do número do telefone.

Resultado:

$parte1 = "99123"

– substr($telefone, 7)  
Extrai os caracteres restantes a partir da posição 7,
que correspondem aos últimos quatro dígitos do telefone.

Resultado:

$parte2 = "4567"

– return "($ddd) $parte1-$parte2";  
Monta e retorna o telefone já formatado no padrão brasileiro.

Resultado final:

"(48) 99123-4567"

*/

function formatar_telefone($telefone) {
        $ddd = substr($telefone, 0, 2);
        $parte1 = substr($telefone, 2, 5);
        $parte2 = substr($telefone, 7);
        return "($ddd) $parte1-$parte2";
}

?>



