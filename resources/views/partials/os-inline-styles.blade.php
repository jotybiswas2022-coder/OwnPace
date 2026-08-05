@php
    $osCss = '';
    $osManifestPath = public_path('build/manifest.json');
    if (file_exists($osManifestPath)) {
        $osManifest = json_decode(file_get_contents($osManifestPath), true);
        $osCssFile = $osManifest['resources/css/app.css']['file'] ?? null;
        $osCssPath = $osCssFile ? public_path('build/'.$osCssFile) : null;
        if ($osCssPath && file_exists($osCssPath)) {
            $osCss = file_get_contents($osCssPath);
        }
    }
@endphp
@if($osCss)
<style>{!! $osCss !!}</style>
@endif
