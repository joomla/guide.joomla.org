// The play button opens the dialog. The playground loads the first time it opens and keeps running while the page is
// open, so closing the dialog by mistake loses nothing.
document.querySelectorAll('[data-playgroundtour]').forEach((module) => {
  const dialog = module.querySelector('dialog');
  const frame = dialog.querySelector('iframe');

  module.querySelector('[data-playgroundtour-open]').addEventListener('click', () => {
    if (!frame.src) {
      frame.src = frame.dataset.src;
    }

    dialog.showModal();
  });

  dialog.querySelector('[data-playgroundtour-close]').addEventListener('click', () => dialog.close());

  // In a new tab the tour starts again from its first step: the playground here is no longer needed.
  dialog.querySelector('[data-playgroundtour-tab]').addEventListener('click', () => {
    dialog.close();
    frame.removeAttribute('src');
  });
});
