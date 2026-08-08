(function () {
    var out = document.createElement('pre');
    out.id = 'diag-output';
    out.style.cssText = 'position:fixed;top:0;left:0;z-index:99999;background:#000;color:#0f0;font:12px monospace;padding:8px;max-width:100%;white-space:pre-wrap;';
    var doc = document.documentElement;
    var report = [];
    report.push('innerWidth=' + window.innerWidth);
    report.push('scrollWidth=' + doc.scrollWidth);
    report.push('clientWidth=' + doc.clientWidth);
    report.push('HORIZONTAL_OVERFLOW=' + (doc.scrollWidth > window.innerWidth ? 'YES' : 'no'));

    // Check key elements
    var aside = document.querySelector('aside');
    if (aside) {
        var r = aside.getBoundingClientRect();
        report.push('aside.left=' + Math.round(r.left) + ' aside.width=' + Math.round(r.width) + ' aside.visible=' + (r.width > 0 && r.left >= -5));
    }
    var hamburger = document.querySelector('.ac-hamburger');
    if (hamburger) {
        var hr = hamburger.getBoundingClientRect();
        report.push('hamburger.visible=' + (hr.width > 0) + ' left=' + Math.round(hr.left));
    }
    var hero = document.querySelector('.dash-hero');
    if (hero) {
        var h2 = hero.getBoundingClientRect();
        report.push('hero.width=' + Math.round(h2.width) + ' hero.right=' + Math.round(h2.right) + ' viewport=' + window.innerWidth);
    }
    var stats = document.querySelectorAll('.stat-card');
    var maxRight = 0;
    stats.forEach(function (s) {
        var sr = s.getBoundingClientRect();
        if (sr.right > maxRight) maxRight = sr.right;
    });
    report.push('statcards.count=' + stats.length + ' maxRight=' + Math.round(maxRight));

    var bodyBg = getComputedStyle(document.body).backgroundColor;
    report.push('bodyBg=' + bodyBg);

    out.textContent = report.join('\n');
    document.body.appendChild(out);
})();
