<?php

include('analisador.php');

$listatokens = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $analisador = new Analisador();
    $listatokens = $analisador->execute();
} else {

}

?>
<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <title>Analisador Léxico</title>
    <style>

        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');

        html, body {
            height: 100%;
            font-family: 'Manrope';
        }
        .container {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">
    
    <header class="text-center bg-dark d-flex flex-row justify-content-between">
        <h1 class="text-start fs-3 p-2 mb-0 mx-4 text-light"><strong>LEXICAL ANALYZER</strong></h1>
    </header>


    <div class="d-flex align-items-center" style="height: 90%;">
        <div class="container d-flex flex-row justify-content-around align-items-center"> 
            <div class="w-50 m-0">
                <form method="POST" class="mb-3 d-flex w-80 justify-content-center">
                <textarea class="form-control w-50" id="validationCustom01" placeholder=">" name="inputString" rows="1"></textarea>
                    <div class="input-group-append align-items-center d-flex" style="padding-left: 25px;">
                        <button class="btn" type="submit" style="background-color:#212529; color: #f8f9fa; ">analisar</button>
                    </div>
            </form>
            

            </div>
            <div class="w-50 align-items-center d-flex justify-content-center flex-column text-dark">
                <div class="w-100">
                    
                <div class="bg-dark rounded-top px-4 py-2 w-100">
                    <h4 class="text-light mb-0"><strong> <?php echo "{$_POST['inputString']}" ?></strong></h4>
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

            foreach ($listatokens as $item) {
                
                echo "<td class='text-dark text-center'>
                <span style='color: gray;'>&lt;</span>
                <strong style='font-size:15px;'>{$item->getToken()}, {$item->getLexema()}</strong>
                <span style='color: gray;'>&gt;</span>
                </td>";
        
                $count++; 
                
                
                if ($count % 4 == 0) {
                    echo "</tr>"; 
                    if ($count < count($listatokens)) {
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