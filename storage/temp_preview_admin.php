<?php

$html = file_get_contents(__DIR__ . '/admin_preview_src.html');

// Inline the app JS module (Alpine + sweetalert) and drop livewire remote bits.
$jsPath = __DIR__ . '/../public/build/assets/app-BCdUdgKt.js';
$js = file_exists($jsPath) ? file_get_contents($jsPath) : '';

$html = preg_replace(
    '#<script type="module" src="http://localhost/build/assets/app-[^"]+\.js"[^>]*></script>#',
    '<script type="module">' . $js . '</script>',
    $html
);

// Drop livewire scripts (not needed for static preview)
$html = preg_replace('#<script src="http://localhost/livewire[^>]*></script>#', '', $html);

// Fix any absolute http://localhost references to relative
$html = str_replace('http://localhost', '', $html);

file_put_contents(__DIR__ . '/admin_preview.html', $html);
echo 'preview size: ' . strlen($html) . PHP_EOL;
