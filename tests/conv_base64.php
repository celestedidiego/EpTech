<?php
// Percorso dell'immagine di input (modifica con il percorso corretto)
$input_image_path = './acer.jpg';

// 1. Apri l'immagine e ottieni i dati binari
$image_data = file_get_contents($input_image_path);

// 2. Converte i dati binari in base64
$base64_string = base64_encode($image_data);

// 3. Salva i dati binari decodificati in un nuovo file JPG
$output_image_path = './acer_output.jpg';
file_put_contents($output_image_path, $base64_string);

echo "Immagine salvata come $output_image_path";
?>
