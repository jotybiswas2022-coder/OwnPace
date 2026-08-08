<?php

$html = file_get_contents(__DIR__ . '/admin_preview.html');
$diag = file_get_contents(__DIR__ . '/temp_diag4.js');
$html = str_replace('</body>', '<script>' . $diag . '</script></body>', $html);
file_put_contents(__DIR__ . '/admin_diag4.html', $html);
file_put_contents(__DIR__ . '/../public/admin_preview.html', $html);
echo 'ok ' . strlen($html) . PHP_EOL;
