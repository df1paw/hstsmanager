(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var section = document.getElementById('hsts');
		if (!section) {
			return;
		}

		var maxAgeInput = document.getElementById('hsts-max-age');
		var includeSubDomainsInput = document.getElementById('hsts-include-subdomains');
		var preloadInput = document.getElementById('hsts-preload');
		var status = document.getElementById('hsts-status');

		var timer = null;

		function showStatus(message, isError) {
			status.textContent = message;
			status.classList.toggle('hsts-status--error', !!isError);
			status.classList.toggle('hsts-status--success', !isError);

			window.clearTimeout(timer);
			timer = window.setTimeout(function () {
				status.textContent = '';
			}, 3000);
		}

		function save() {
			var maxAge = parseInt(maxAgeInput.value, 10);

			if (isNaN(maxAge) || maxAge < 0) {
				showStatus(t('hstsmanager', 'Please enter a valid, non-negative number of seconds.'), true);
				return;
			}

			fetch(OC.generateUrl('/apps/hstsmanager/settings'), {
				method: 'PUT',
				headers: {
					'Content-Type': 'application/json',
					'requesttoken': OC.requestToken
				},
				body: JSON.stringify({
					maxAge: maxAge,
					includeSubDomains: includeSubDomainsInput.checked,
					preload: preloadInput.checked
				})
			})
				.then(function (response) {
					if (!response.ok) {
						throw new Error('Request failed');
					}
					return response.json();
				})
				.then(function () {
					showStatus(t('hstsmanager', 'Saved'), false);
				})
				.catch(function () {
					showStatus(t('hstsmanager', 'Could not save the settings.'), true);
				});
		}

		maxAgeInput.addEventListener('change', save);
		includeSubDomainsInput.addEventListener('change', save);
		preloadInput.addEventListener('change', save);
	});
})();
