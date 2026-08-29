/**
 * Botón de copiar en cada bloque de código, y barra de progreso de lectura.
 *
 * Sin dependencias y sin tocar el marcado del servidor: todo se monta al
 * cargar, así que una entrada escrita hace dos años lo hereda sin editarla.
 */
(function () {
  'use strict';

  var i18n = window.nestjslatamI18n || {
    copy: 'Copiar',
    copied: 'Copiado',
    failed: 'No se pudo copiar',
  };

  function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }

    // Reserva para http:// y navegadores sin permiso de portapapeles.
    return new Promise(function (resolve, reject) {
      var area = document.createElement('textarea');
      area.value = text;
      area.setAttribute('readonly', '');
      area.style.position = 'fixed';
      area.style.opacity = '0';
      document.body.appendChild(area);
      area.select();

      try {
        document.execCommand('copy') ? resolve() : reject();
      } catch (error) {
        reject(error);
      } finally {
        document.body.removeChild(area);
      }
    });
  }

  function addCopyButtons() {
    var blocks = document.querySelectorAll('.entry-content pre');

    Array.prototype.forEach.call(blocks, function (pre) {
      if (pre.parentNode.classList.contains('nl-pre-wrap')) {
        return;
      }

      var wrap = document.createElement('div');
      wrap.className = 'nl-pre-wrap';
      pre.parentNode.insertBefore(wrap, pre);
      wrap.appendChild(pre);

      var button = document.createElement('button');
      button.type = 'button';
      button.className = 'nl-copy';
      button.textContent = i18n.copy;
      button.setAttribute('aria-label', i18n.copy);

      button.addEventListener('click', function () {
        copyText(pre.innerText)
          .then(function () {
            button.textContent = i18n.copied;
            button.dataset.state = 'done';
          })
          .catch(function () {
            button.textContent = i18n.failed;
          })
          .then(function () {
            window.setTimeout(function () {
              button.textContent = i18n.copy;
              delete button.dataset.state;
            }, 2000);
          });
      });

      wrap.appendChild(button);
    });
  }

  function addReadingProgress() {
    if (!document.body.classList.contains('single')) {
      return;
    }

    var article = document.querySelector('.entry-content');
    if (!article) {
      return;
    }

    var bar = document.createElement('div');
    bar.className = 'nl-progress';
    bar.setAttribute('role', 'presentation');
    document.body.appendChild(bar);

    var ticking = false;

    function update() {
      var rect = article.getBoundingClientRect();
      var total = rect.height - window.innerHeight;
      var progress = total > 0 ? (-rect.top / total) * 100 : 0;

      bar.style.width = Math.min(100, Math.max(0, progress)) + '%';
      ticking = false;
    }

    // rAF en vez de correr en cada evento de scroll: el navegador decide
    // cuándo, y no se encolan cien cálculos por gesto.
    window.addEventListener(
      'scroll',
      function () {
        if (!ticking) {
          window.requestAnimationFrame(update);
          ticking = true;
        }
      },
      { passive: true }
    );

    update();
  }

  function init() {
    addCopyButtons();
    addReadingProgress();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
