(function () {
    var doc = document.documentElement;
    var out = document.createElement('pre');
    out.id = 'diag-output';
    out.style.cssText = 'position:fixed;top:0;left:0;z-index:99999;background:#000;color:#0f0;font:12px monospace;padding:8px;max-width:100%;white-space:pre-wrap;';
    var report = [];
    report.push('innerWidth=' + window.innerWidth);
    report.push('doc.scrollWidth=' + doc.scrollWidth);

    // Find widest elements
    var all = document.querySelectorAll('*');
    var offenders = [];
    all.forEach(function (el) {
        var r = el.getBoundingClientRect();
        if (r.width > 0 && r.right > window.innerWidth + 2 && el.tagName !== 'HTML' && el.tagName !== 'BODY') {
            offenders.push(el.tagName + '.' + (el.className && el.className.toString ? el.className.toString().substring(0, 60) : '') + ' right=' + Math.round(r.right) + ' width=' + Math.round(r.width));
        }
    });
    report.push('OFFENDERS=' + (offenders.length ? '\n' + offenders.slice(0, 15).join('\n') : 'none'));

    // Stage rect
    var stage = document.querySelector('.ac-stage');
    if (stage) {
        var sr = stage.getBoundingClientRect();
        report.push('stage.left=' + Math.round(sr.left) + ' stage.right=' + Math.round(sr.right));
    }

    out.textContent = report.join('\n');
    document.body.appendChild(out);
})();
