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
        success: null,
        error: null,
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

    var config = {
        url: url,
        method: options.type,
		data: options.data
    };

	return new Promise((resolve, reject) => {
		axios(config)
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

        if (options.success)
            options.success(data);

        requestFinally(options, data);

        stopLoader('success');
	} catch(ex) {
		console.log(ex);
        stopLoader('error');
	}
}

let requestFail = function(options, errors)
{  
    stopLoader('error');

	console.log('Request failed', options);
	console.log('Errors', errors);

    if (options.error)
        options.error(errors);

	requestFinally(options, errors);
}

let requestFinally = function (options, data) {
    throwAlerts(data);

    if (options.complete)
        options.complete(data);

    if (data.redirect)
        window.location.replace(data.redirect);
}

let throwAlerts = function(data)
{  
	if (data.alerts && data.alerts.length) {
		data.alerts.forEach(function(alert) {
			Alerts.add(alert.message, alert.tag, alert.time);
		}, this);
	}
}