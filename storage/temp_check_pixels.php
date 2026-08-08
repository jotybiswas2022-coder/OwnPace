<?php

function analyze($path, $label)
{
    if (!file_exists($path)) { echo "$label: MISSING\n"; return; }
    $im = imagecreatefrompng($path);
    $w = imagesx($im);
    $h = imagesy($im);

    // Left edge strip (x=30): is it the indigo sidebar (#2e2a6b-ish) or paper (#ececea)?
    $left = imagecolorat($im, 30, intval($h / 2));
    $l = [($left >> 16) & 0xFF, ($left >> 8) & 0xFF, $left & 0xFF];

    // Top area (y=40, center): hero gradient dark purple vs paper
    $top = imagecolorat($im, intval($w / 2), 40);
    $t = [($top >> 16) & 0xFF, ($top >> 8) & 0xFF, $top & 0xFF];

    // Mid-right area: light card background
    $mid = imagecolorat($im, intval($w * 0.85), intval($h / 2));
    $m = [($mid >> 16) & 0xFF, ($mid >> 8) & 0xFF, $mid & 0xFF];

    printf("%s: %dx%d | left(rgb%d,%d,%d) top(%d,%d,%d) rightmid(%d,%d,%d)\n",
        $label, $w, $h, $l[0], $l[1], $l[2], $t[0], $t[1], $t[2], $m[0], $m[1], $m[2]);
    imagedestroy($im);
}

analyze(__DIR__ . '/shot_desktop2.png', 'DESKTOP');
analyze(__DIR__ . '/shot_mobile2.png', 'MOBILE');
