<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="get">

        <textarea name="formulario" id="formulario" name="formulario" cols="25" rows="10"></textarea>
        <br>
        <input type="submit">
    </form>
    <br>
    <?php
    echo "teste";
        include("token.php");
        echo "teste";
        include("Analisador_lexico.php");
        echo "teste";
        include("Analisador_sintatico.php");
        echo "teste";
        if(isset($_GET["formulario"])){
            $entrada = $_GET["formulario"];
            $analisador = new Analisador_lexico();
            $analisador->analisa($entrada);
            $dr = new analisador_sintatico($analisador);
            print_r($dr->lexico->tokens);
            print_r($dr->Programa());
        } 
    ?>
</body>
</html>