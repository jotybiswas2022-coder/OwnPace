<?php

$html = file_get_contents(__DIR__ . '/admin_preview.html');
$diag = file_get_contents(__DIR__ . '/temp_diag2.js');
$html = str_replace('</body>', '<script>' . $diag . '</script></body>', $html);
file_put_contents(__DIR__ . '/admin_diag2.html', $html);
echo 'ok ' . strlen($html) . PHP_EOL;
