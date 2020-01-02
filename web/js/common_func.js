/* COMMON FUNCTIONS */
const serialize = (form) => {
	// Setup our serialized data
	const serialized = [];

	// Loop through each field in the form
	for (let i = 0; i < form.elements.length; i++) {
		const field = form.elements[i];

		// Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
		if (
			!field.name ||
			field.disabled ||
			field.type === 'file' ||
			field.type === 'reset' ||
			field.type === 'submit' ||
			field.type === 'button'
		)
			continue;

		// If a multi-select, get all selections
		if (field.type === 'select-multiple') {
			for (let n = 0; n < field.options.length; n++) {
				if (!field.options[n].selected) continue;
				serialized.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(field.options[n].value));
			}
		} else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
			// Convert field data to a query string
			serialized.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value));
		}
	}

	return serialized.join('&');
};

const post = (url = '', data = {}, content_type = 'application/x-www-form-urlencoded') => {
	return fetch(url, {
		method: 'POST',
		body: data,
		headers: {
			'Content-Type': content_type,
			Authorization: 'Bearer header'
		}
	}).then((response) => {
		if (response.ok) {
			return response.json();
		} else {
			throw new Error('Something Went wrong');
		}
	});
};
