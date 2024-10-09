<?php

include('analisador.php');


?>


<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <title>Analisador Léxico</title>
    <style>
        html, body {
            height: 100%;
        }
        .container {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">
    
    <header class="text-center bg-dark d-flex flex-row justify-content-between">
        <h1 class="text-start fs-3 p-2 mx-4 text-light">LEXICAL ANALYZER</h1>
    </header>


    <div class="d-flex align-items-center" style="height: 90%;">
        <div class="container d-flex flex-row justify-content-around align-items-center"> 
            <div class="w-25 m-0">
                <form method="POST" class="mb-3">
                    
                    <div class="input-group mb-3">
                        <input type="text" class="form-control py-2" placeholder="Digite a string" name="inputString">
                        <div class="input-group-append">
                            <button class="btn btn-outline-dark py-2" type="submit" style="border-top-left-radius: 0rem; border-bottom-left-radius: 0rem;">Analisar</button>
                        </div>
                    </div>
                    </form>

            </div>
            <div class="w-50 align-items-center d-flex justify-content-center flex-column text-dark">
                <div class="w-100">
                    
                <div class="bg-dark rounded-top px-4 py-2 w-100">
                    <h4 class="text-light mb-0"> <?php echo "{$_POST['inputString']}" ?></h4>
                </div>

                </div>

                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="text-dark text-center" colspan="4">TOKENS</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        
                        <?php 
            echo "<tr>"; 
            $count = 0; 
            
            foreach ($GLOBALS['listatokens'] as $item) {
                
                echo "<td class='text-dark text-center'>
                <span style='color: gray;'>&lt;</span>
                <strong>{$item->getToken()}, {$item->getLexema()}</strong>
                <span style='color: gray;'>&gt;</span>
                </td>";
        
                $count++; 
                
                
                if ($count % 4 == 0) {
                    echo "</tr>"; 
                    if ($count < count($GLOBALS['listatokens'])) {
                        echo "<tr>"; 
                    }
                }
            }
            
            if ($count % 4 != 0) {
                echo "</tr>";
            }
            ?>



</div>
</div>
</div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
</html>