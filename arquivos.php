<?php

$gs_name  = $_FILES['arquivo']['tmp_name'];
$filename = 'arquivodetestenovo.xlsx';
move_uploaded_file($gs_name, "gs://mb-cadastro-arquivos/arquivodetestenovo.xlsx");
//rename("gs://mb-cadastro-arquivos/$gs_name","gs://mb-cadastro-arquivos/$filename");
echo 'funciono';
?>