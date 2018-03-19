function startLoader() {
    window.Loader.show();
}
function stopLoader(result) {
    window.Loader.done(result);
}

// Ajax block replace request
$.fn.request = function(options)
{  
	let defaults = {
		url: false,
		type: 'post',
		controller: '',
		action: 'index',
		loader: 'global',
		language: language,
		complete: null,
		data: {}
	}; 
	
	var opts = $.extend(defaults, options);

	opts.replace = true;

	if (this.length == 0)
		opts.replace = false;
		
	if (this.length > 1) {
		console.log('Object should have single identifier');
		return false;
	}

	opts.element = this;

	if (this.attr('id') == undefined)
		this.attr('id', Math.random().toString(8));

	opts.oc = this.attr('id');

	startLoader();
	
	return request(opts);
}

// Json simple request
$.request = function(options)
{  
	return $("#json").request(options);
}

let request = function(options)
{  
	// $(options.element).data('callbacks', { complete: options.complete })
	// 				  .data('loader', { type: options.loader });

	let url = '/admin/' + language + '/' + options.controller + '/' + options.action;

	if (options.url)
		url = options.url;

	return new Promise((resolve, reject) => {
		axios[options.type](url, options.data)
			.then(response => {
				requestSuccess(options, response.data);
			})
			.catch(error => {
				requestFail(options, error.response.data);
			});
	});
}

let requestSuccess = function(options, data)
{  
	try	{
		if (options.oc !== 'json')
			$(options.element).replaceWith(data.content);

		if (data.js !== '')
			eval(data.js);
			
		if (options.complete)
			options.complete(data);
	} catch(ex) {
		console.log(ex);
	}

	throwAlerts(data);
    stopLoader('success');
}

let requestFail = function(options, errors)
{  
	throwAlerts(errors);
    stopLoader('error');

	console.log('Request failed', options);
	console.log('Errors', errors);
}

let throwAlerts = function(data)
{  
	if (data.alerts && data.alerts.length) {
		data.alerts.forEach(function(alert) {
			Alerts.add(alert.message, alert.tag, alert.time);
		}, this);
	}
}