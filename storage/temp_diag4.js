(function () {
    function measure() {
        var out = document.createElement('pre');
        out.id = 'diag-output';
        out.style.cssText = 'position:fixed;top:0;left:0;z-index:99999;background:#000;color:#0f0;font:12px monospace;padding:8px;max-width:100%;white-space:pre-wrap;';
        var doc = document.documentElement;
        var report = [];
        report.push('innerWidth=' + window.innerWidth);
        report.push('doc.scrollWidth=' + doc.scrollWidth);
        report.push('HORIZONTAL_OVERFLOW=' + (doc.scrollWidth > window.innerWidth ? 'YES' : 'no'));
        report.push('Alpine.loaded=' + (typeof window.Alpine !== 'undefined' ? 'yes' : 'no'));
        report.push('Alpine.started=' + (window.Alpine && window.Alpine.start ? 'api-present' : 'no-api'));

        var aside = document.querySelector('aside');
        if (aside) {
            var r = aside.getBoundingClientRect();
            report.push('aside.left=' + Math.round(r.left) + ' aside.width=' + Math.round(r.width) + ' aside.visible=' + (r.width > 0 && r.left >= -5));
            report.push('aside.cloak=' + (aside.hasAttribute('x-cloak')));
            report.push('aside.style.display=' + getComputedStyle(aside).display);
            report.push('aside.x-show=' + aside.getAttribute('x-show'));
        }
        var hamburger = document.querySelector('.ac-hamburger');
        if (hamburger) {
            report.push('hamburger.display=' + getComputedStyle(hamburger).display);
        }
        var stage = document.querySelector('.ac-stage');
        if (stage) {
            var sr = stage.getBoundingClientRect();
            report.push('stage.right=' + Math.round(sr.right));
        }
        var all = document.querySelectorAll('*');
        var offenders = [];
        all.forEach(function (el) {
            var r = el.getBoundingClientRect();
            if (r.width > 0 && r.right > window.innerWidth + 2 && el.tagName !== 'HTML' && el.tagName !== 'BODY') {
                offenders.push(el.tagName + '.' + String(el.className).substring(0, 40) + ' right=' + Math.round(r.right));
            }
        });
        report.push('OFFENDERS=' + (offenders.length ? offenders.slice(0, 8).join(' | ') : 'none'));
        out.textContent = report.join('\n');
        document.body.appendChild(out);
    }
    window.addEventListener('load', function () { setTimeout(measure, 800); });
    setTimeout(measure, 2000);
})();
