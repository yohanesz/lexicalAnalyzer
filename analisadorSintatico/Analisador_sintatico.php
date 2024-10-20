<?php

class analisador_sintatico {
    private $cont = 0;
    public $lexico;

    public function __construct(analisador_lexico $lexico){
        $this->lexico = $lexico;
    }

    public function termo($tk){
        print('termo');
        var_dump($tk == $this->lexico->tokens[$this->cont++]->tok);
        return $tk == $this->lexico->tokens[$this->cont++]->tok;
    }

    private $erros = [];

    public function getErros() {
        return $this->erros;
    }

    public function vazio() {
        return true; // Representa produção vazia
    }

    //<PROGRAMA> ::= PROGRAMA ID ABRE_PARENT <LISTA_VAR> FECHA FECHA_PARENT ABRE_CHAVE <LISTA_COMANDOS> FECHA_CHAVE
    //<LISTA_VAR> ::= <VAR> <LISTA_VAR> | î
    //<VAR> ::= <TIPO> ID PONTO_VIRGULA
    //<TIPO> ::= INT | FLOAT | CHAR | ARRAY
    //<LISTA_COMANDOS> ::= <COMANDO> <LISTA_COMANDOS> | Î
    //<COMANDO> ::= <ATRIBUICAO> | <LEITURA> | <IMPRESSAO> | <RETORNO> | <CHAMADA_FUNCAO> | <IF> | <FOR> | <WHILE> 
    //<ATRIBUICAO> ::= ID ATRIBUICAO <EXPRESSAO> PONTO_VIRGULA
    //<LEITURA> ::= LEIA ABRE_PARENT ID FECHA_PARENT PONTO_VIRGULA
    //<IMPRESSAO> ::= IMPRIMA ABRE_PARENT <EXPRESSAO> FECHA_PARENT PONTO_VIRGULA
    //<RETORNO> ::= RETORNE <EXPRESSAO> PONTO_VIRGULA (IMPLEMENTAR RETORNE)
    //<CHAMADA_FUNCAO> ::= ID ABRE_PARENT <ATRIBUTOS> FECHA_PARENT PONTO_VIRGULA
    //<IF> SE ABRE_PARENT <EXPRESSAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    //<FOR> PARA ABRE_PARENT <ATRIBUICAO> <EXPRESSAO> PONTO_VIRGULA <ATRIBUICAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    //<WHILE> ENQUANTO ABRE_PARENT <EXPRESSAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    //<EXPRESSAO> ::= <TERMO> (SOMA | SUB | OPERADOR_LOGICO) <TERMO> 
    //<OPERADOR_LOGICO> ::= IGUAL | DIFERENTE | MENOR_QUE | MAIOR_QUE | MENOR_OU_IGUAL | MAIOR_OU_IGUAL | NEGACAO (IMPLEMENTAR OS FALTANTES) *
    //<TERMO> ::= <FATOR> (MULTI | DIV | MOD ) <FATOR> *
    //<FATOR> ::= ID | CONST | ABRE_PARENT <EXPRESSAO> FECHA_PARENT

    //<PROGRAMA> ::= PROGRAMA ID ABRE_PARENT <LISTA_VAR> FECHA_PARENT ABRE_CHAVE <LISTA_COMANDOS> FECHA_CHAVE
    public function PROGRAMA() {
        if( $this->termo('PROGRAMA') && 
            $this->termo('ID') && 
            $this->termo('ABRE_PARENT') &&
            $this->LISTA_VAR() &&
            $this->termo('FECHA_PARENT') &&
            $this->termo('ABRE_CHAVE') &&
            $this->LISTA_COMANDOS() &&
            $this->termo('FECHA_CHAVE')) {

            return true;
        } else {
            return false;
        }
    }

    //<LISTA_VAR> ::= <VAR> <LISTA_VAR> | î
    public function LISTA_VAR() {
        if ($this->VAR()) {
            return $this->LISTA_VAR(); 
        }
        return $this->vazio(); 
    }

    //<VAR> ::= <TIPO> ID PONTO_VIRGULA
    public function VAR() {
        return $this->Tipo() && 
            $this->termo('ID') && 
            $this->termo('PONTO_VIRGULA');
    }

    //<TIPO> ::= INT | FLOAT | CHAR | ARRAY
    public function TIPO() {
        return $this->termo('INT') || $this->termo('FLOAT') ||
               $this->termo('CHAR') || $this->termo('ARRAY');
    }

    //<LISTA_COMANDOS> ::= <COMANDO> <LISTA_COMANDOS> | Î
    public function LISTA_COMANDOS() {
        if ($this->COMANDO()) {
            return $this->LISTA_COMANDOS();
        }
        return $this->vazio(); 
    }
    
    //<COMANDO> ::= <ATRIBUICAO> | <LEITURA> | <IMPRESSAO> | <RETORNO> | <CHAMADA_FUNCAO> | <IF> | <FOR> | <WHILE> 
    public function COMANDO() {
        return $this->ATRIBUICAO() ||
               $this->LEITURA() ||
               $this->IMPRESSAO() ||
               $this->RETORNO() ||
               $this->CHAMADA_FUNCAO() ||
               $this->IF() ||
               $this->FOR() ||
               $this->WHILE();
    }

    //<ATRIBUICAO> ::= ID ATRIBUICAO <EXPRESSAO> PONTO_VIRGULA
    public function ATRIBUICAO() {
        return $this->termo('ID') && 
               $this->termo('ATRIBUICAO') && 
               $this->EXPRESSAO() && 
               $this->termo('PONTO_VIRGULA');
    }

    //<LEITURA> ::= LEIA ABRE_PARENT ID FECHA_PARENT PONTO_VIRGULA
    public function LEITURA() {
        return $this->termo('LEIA') &&
               $this->termo('ABRE_PARENT')&& 
               $this->termo('ID') && 
               $this->termo('FECHA_PARENT') && 
               $this->termo('PONTO_VIRGULA');
    }

    //<IMPRESSAO> ::= IMPRIMA ABRE_PARENT <EXPRESSAO> FECHA_PARENT PONTO_VIRGULA
    public function IMPRESSAO() {
        return $this->termo('IMPRIMA') && 
               $this->termo('ABRE_PARENT') && 
               $this->EXPRESSAO() && 
               $this->termo('FECHA_PARENT') && 
               $this->termo('PONTO_VIRGULA');
    }

    //<RETORNO> ::= RETORNE <EXPRESSAO> PONTO_VIRGULA (IMPLEMENTAR RETORNE)
    public function RETORNO() {
        return $this->termo('RETORNE') && 
               $this->EXPRESSAO() && 
               $this->termo('PONTO_VIRGULA');
    }

    //<CHAMADA_FUNCAO> ::= ID ABRE_PARENT <ATRIBUTOS> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    public function CHAMADA_FUNCAO() {
        return $this->termo('ID') && 
               $this->termo('ABRE_PARENT') && 
               $this->EXPRESSAO() && 
               $this->termo('FECHA_PARENT') &&
               $this->termo('ABRE_CHAVE') &&
               $this->COMANDO() &&
               $this->termo('FECHA_CHAVE');
    }

    //<IF> SE ABRE_PARENT <EXPRESSAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    public function IF() {
        return $this->termo('SE') && 
               $this->termo('ABRE_PARENT') && 
               $this->EXPRESSAO() && 
               $this->termo('FECHA_PARENT') && 
               $this->termo('ABRE_CHAVE') && 
               $this->COMANDO() && 
               $this->termo('FECHA_CHAVE');
    }

    //<FOR> PARA ABRE_PARENT <ATRIBUICAO> <EXPRESSAO> PONTO_VIRGULA <ATRIBUICAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    public function For() {
        return $this->termo('PARA') && 
               $this->termo('ABRE_PARENT') && 
               $this->ATRIBUICAO() &&  
               $this->EXPRESSAO() && 
               $this->termo('PONTO_VIRGULA') && 
               $this->Atribuicao() && 
               $this->termo('FECHA_PARENT') && 
               $this->termo('ABRE_CHAVE') && 
               $this->COMANDO() && 
               $this->termo('FECHA_CHAVE');
    }

    //<WHILE> ENQUANTO ABRE_PARENT <EXPRESSAO> FECHA_PARENT ABRE_CHAVE <COMANDO> FECHA_CHAVE
    public function While() {
        return $this->termo('ENQUANTO') && 
               $this->termo('ABRE_PARENT') && 
               $this->EXPRESSAO() && 
               $this->termo('FECHA_PARENT') && 
               $this->termo('ABRE_CHAVE') && 
               $this->COMANDO() && 
               $this->termo('FECHA_CHAVE');
    }

    //<EXPRESSAO> ::= <TERMO> (SOMA | SUB | OPERADOR_LOGICO) <TERMO> 
    public function EXPRESSAO() {
        if ($this->TERMOS()) {
            while ($this->termo('SOMA') || 
                $this->termo('SUB') || 
                $this->OPERADOR_LOGICO()) {
                $this->TERMOS();
            }
            return true;
        }
        return false;
    }

    //<OPERADOR_LOGICO> ::= IGUAL | DIFERENTE | MENOR_QUE | MAIOR_QUE | MENOR_OU_IGUAL | MAIOR_OU_IGUAL | NEGACAO (IMPLEMENTAR OS FALTANTES) *
    public function OPERADOR_LOGICO() {
        return $this->termo('IGUAL') || 
            $this->termo('DIFERENTE') || 
            $this->termo('MENOR_QUE') || 
            $this->termo('MAIOR_QUE') || 
            $this->termo('MENOR_OU_IGUAL') || 
            $this->termo('MAIOR_OU_IGUAL') || 
            $this->termo('NEGACAO');
    }

    //<TERMO> ::= <FATOR> (MULTI | DIV | MOD ) <FATOR> *
    public function TERMOS() {
        if ($this->FATOR()) {
            while ($this->termo('MULTIO') || $this->termo('DIV')) {
                $this->FATOR();
            }
            return true;
        }
        return false;
    }

    //<FATOR> ::= ID | CONST | ABRE_PARENT <EXPRESSAO> FECHA_PARENT
    public function FATOR() {
        return $this->termo('ID') || 
               $this->termo('CONST') || 
               ($this->termo('ABRE_PARENTESES') && $this->Expressao() && $this->termo('FECHA_PARENTESES'));
    }

    


}
?>
