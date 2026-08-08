<?php

$html = file_get_contents(__DIR__ . '/admin_preview.html');
$diag = file_get_contents(__DIR__ . '/temp_diag.js');

// Inject diagnostic right before </body>
$html = str_replace('</body>', '<script>' . $diag . '</script></body>', $html);

file_put_contents(__DIR__ . '/admin_diag.html', $html);
echo 'diag page: ' . strlen($html) . PHP_EOL;
