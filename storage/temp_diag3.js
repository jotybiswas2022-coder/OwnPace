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

        var aside = document.querySelector('aside');
        if (aside) {
            var r = aside.getBoundingClientRect();
            report.push('aside.left=' + Math.round(r.left) + ' aside.width=' + Math.round(r.width) + ' aside.visible=' + (r.width > 0 && r.left >= -5));
            report.push('aside.cloak=' + (aside.getAttribute('x-cloak') !== null));
            report.push('aside.style.display=' + getComputedStyle(aside).display);
        }
        var hamburger = document.querySelector('.ac-hamburger');
        if (hamburger) {
            var hr = hamburger.getBoundingClientRect();
            report.push('hamburger.visible=' + (hr.width > 0));
            report.push('hamburger.display=' + getComputedStyle(hamburger).display);
        }
        var hero = document.querySelector('.dash-hero');
        if (hero) {
            var h2 = hero.getBoundingClientRect();
            report.push('hero.width=' + Math.round(h2.width));
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
                offenders.push(el.tagName + '.' + String(el.className).substring(0, 50) + ' right=' + Math.round(r.right));
            }
        });
        report.push('OFFENDERS=' + (offenders.length ? offenders.slice(0, 10).join(' | ') : 'none'));

        out.textContent = report.join('\n');
        document.body.appendChild(out);
    }

    // Wait for Alpine boot + paint, then measure.
    window.addEventListener('load', function () {
        setTimeout(measure, 600);
    });
    setTimeout(measure, 1500); // fallback
})();
