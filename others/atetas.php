<?php
require_once('analisador_lexico.php');

class analisador_sintatico {
    private $tokens;
    private $currentTokenIndex = 0;
    private $cont = 0;

    public function __construct($tokens) {
        $this->tokens = $tokens;
    }

    public function vazio(){
        return true;
    }

    public function termo($expectedToken) {
        if ($this->tokens[$this->currentTokenIndex] == $expectedToken) {
            $this->currentTokenIndex++; 
            return true;
        } else {
            $this->erro("Esperado '$expectedToken'");
            return false;
        }
    }

    public function erro($mensagem) {
        $tokenAtual = $this->tokens[$this->currentTokenIndex] ?? 'EOF'; 
        echo "Erro: $mensagem. Token atual: '$tokenAtual'. Posição: $this->currentTokenIndex\n";
    }

    public function start() {
        return $this->PROGRAMA();
    }

    //<PROGRAMA> ::= PROGRAMA ID ABRE_CHAVE <LISTA_COMANDOS> FECHA_CHAVE
    public function PROGRAMA() {
        return $this->termo('PROGRAMA') &&
               $this->termo('ID') &&
               $this->termo('ABRE_CHAVE') &&
               $this->LISTA_COMANDOS() &&
               $this->termo('FECHA_CHAVE');
    }

    public function BLOCO() {
        return $this->termo('ABRE_CHAVE') &&
               $this->LISTA_COMANDOS() &&
               $this->termo('FECHA_CHAVE');
    }

    public function LISTA_COMANDOS() {
        while ($this->COMANDO()) {}
        return true;
    }

    public function COMANDO() {
        return $this->DECLARACAO_VAR() ||
               $this->FACA() ||
               $this->IMPRIMA() ||
               $this->LEIA() ||
               $this->RETORNO() ||
               $this->SE() ||
               $this->ENQUANTO() ||
               $this->PARA() ||
               $this->ATRIBUICAO();
    }

    public function FACA() {
        return $this->termo('FACA') &&
               $this->termo('ABRE_PARENT') &&
               $this->BLOCO() &&
               $this->termo('FECHA_PARENT') &&
               $this->ENQUANTO();
    }

    public function IMPRIMA() {
        return $this->termo('IMPRIMA') &&
               $this->termo('ABRE_PARENT') &&
               $this->EXPR() &&
               $this->termo('FECHA_PARENT') &&
               $this->termo('PONTO_VIRGULA');
    }

    public function LEIA() {
        return $this->termo('LEIA') &&
               $this->termo('ABRE_PARENT') &&
               $this->termo('ID') &&
               $this->termo('FECHA_PARENT') &&
               $this->termo('PONTO_VIRGULA');
    }

    public function RETORNO() {
        if ($this->termo('RETORNO')) {
            if ($this->EXPR()) {
                return $this->termo('PONTO_VIRGULA');
            }
            return $this->termo('PONTO_VIRGULA');
        }
        return false;
    }

    public function DECLARACAO_VAR() {
        return $this->TIPO() &&
               $this->termo('ID') &&
               ($this->termo('IGUAL') ? $this->EXPR() : true) &&
               $this->termo('PONTO_VIRGULA');
    }

    public function TIPO() {
        return $this->termo('INT') || $this->termo('FLOAT') ||
               $this->termo('CHAR') || $this->TIPO_ARRAY();
    }

    public function TIPO_ARRAY() {
        return ($this->termo('INT') || $this->termo('FLOAT') || $this->termo('CHAR')) &&
               $this->termo('ABRE_COLCHETE') &&
               $this->termo('FECHA_COLCHETE');
    }

    public function SE() {
        return $this->termo('SE') &&
               $this->termo('ABRE_PARENT') &&
               $this->EXPR() &&
               $this->termo('FECHA_PARENT') &&
               $this->BLOCO() &&
               ($this->termo('SENAO') ? $this->BLOCO() : true);
    }

    public function ENQUANTO() {
        return $this->termo('ENQUANTO') &&
               $this->termo('ABRE_PARENT') &&
               $this->EXPR() &&
               $this->termo('FECHA_PARENT') &&
               $this->BLOCO();
    }

    public function PARA() {
        return $this->termo('PARA') &&
               $this->termo('ABRE_PARENT') &&
               $this->DECLARACAO_VAR() &&
               $this->EXPR() &&
               $this->termo('PONTO_VIRGULA') &&
               $this->EXPR() &&
               $this->termo('FECHA_PARENT') &&
               $this->BLOCO();
    }

    public function ATRIBUICAO() {
        return $this->termo('ID') &&
               $this->termo('IGUAL') &&
               $this->EXPR() &&
               $this->termo('PONTO_VIRGULA');
    }

    public function OPERADOR() {
        return $this->termo('SOMA') || $this->termo('SUB') ||
               $this->termo('MULTI') || $this->termo('DIV') ||
               $this->termo('IGUALDADE') || $this->termo('MOD');
    }

    public function TERMO_ARIT() {
        return $this->termo('ID') || $this->termo('NUMERO');
    }

    public function EXPR() {
        if ($this->termo('ABRE_PARENT')) {
            $result = $this->EXPR();
            if ($result && $this->termo('FECHA_PARENT')) {
                return true;
            }
            return false;
        }

        $result = $this->TERMO_ARIT();
        while ($this->OPERADOR()) {
            if (!$this->TERMO_ARIT()) {
                return false;
            }
        }
        return $result;
    }
}
?>
