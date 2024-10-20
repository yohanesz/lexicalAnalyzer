<?php
require_once('analisador_lexico.php');
require_once('analisador_sintatico.php');

// Captura a entrada do terminal
echo "Digite o código para análise: ";
$handle = fopen("php://stdin", "r");
$entrada = fgets($handle);

// Cria e executa o analisador léxico
$lexico = new analisador_lexico();
$listOfTokens = $lexico->execute($entrada);

// Inicializa o analisador sintático com os tokens gerados
$sintatico = new analisador_sintatico($listOfTokens);

// Executa o analisador sintático
if ($sintatico->PROGRAMA()) { // Ou use a função inicial correta, como start()
    echo "Análise concluída - OK\n";
} else {
    echo "Erro na análise sintática.\n";
}
