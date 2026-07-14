/* ============================================================
   AVA Solution — site behaviour (vanilla JS, no dependencies)
   Replaces the Design-Components runtime: mobile menu toggle,
   FAQ accordion, and the form anti-spam timestamp.
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Mobile menu ---------- */
  var burger = document.getElementById('burger');
  var menu = document.getElementById('mobileMenu');
  if (burger && menu) {
    burger.addEventListener('click', function () {
      menu.classList.toggle('open');
      var open = menu.classList.contains('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    // Close the menu when a link inside it is tapped.
    menu.addEventListener('click', function (e) {
      if (e.target.closest('a')) menu.classList.remove('open');
    });
  }

  /* ---------- FAQ accordion (one open at a time) ---------- */
  var faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(function (item) {
    var btn = item.querySelector('.faq-q');
    if (!btn) return;
    btn.addEventListener('click', function () {
      var isOpen = item.classList.contains('open');
      faqItems.forEach(function (other) {
        other.classList.remove('open');
        var s = other.querySelector('.faq-sign');
        if (s) s.textContent = '+';
        var b = other.querySelector('.faq-q');
        if (b) b.setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        var sign = item.querySelector('.faq-sign');
        if (sign) sign.textContent = '−'; /* minus */
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  /* ---------- Lead form anti-spam timestamp ---------- */
  /* send.php rejects submissions that arrive implausibly fast. */
  document.querySelectorAll('form.lead-form').forEach(function (form) {
    var ts = form.querySelector('input[name="ts"]');
    if (ts) ts.value = String(Date.now());
  });
})();
