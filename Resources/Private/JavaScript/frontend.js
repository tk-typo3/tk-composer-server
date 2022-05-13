document.addEventListener('DOMContentLoaded', function() {
  // Filter packages
  document.getElementById('filter').addEventListener('keyup', function() {
    let filter = this.value;

    document.getElementById('packages').querySelectorAll('.package').forEach(function(element) {
      if (filter.length > 0) {
        if (element.dataset.name.indexOf(filter) < 0
          && element.dataset.description.indexOf(filter) < 0
          && element.dataset.url.indexOf(filter) < 0
        ) {
          element.style.display = 'none';

          return;
        }
      }

      element.style.display = 'block';
    });
  });

  // Register event to copy to clipboard
  document.querySelectorAll('[data-clipboard]').forEach(function(element) {
    element.addEventListener('click', async function() {
      await navigator.clipboard.writeText(element.dataset.clipboard);
    })
  });

  // Toggle login mask
  if (document.getElementById('login-form-button')) {
    document.getElementById('login-form-button').addEventListener('click', function(event) {
      let mask = document.getElementById('login-mask');

      event.preventDefault();

      mask.classList.toggle('d-none');

      if (!mask.classList.contains('d-none')) {
        document.getElementById('fUsername').focus();
      }

      return false;
    });
  }
});
