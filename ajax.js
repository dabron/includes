'use strict';

function httpAsync(method, url, data, callback)
{
	var client = new XMLHttpRequest();

	client.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			var response = this.responseXML !== null ? this.responseXML : this.responseText
			callback(response);
		}
	};

	client.open(method, url);

	if (data !== null)
	{
		client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	}

	client.send(data);
}

function createAsync(url, data, callback)
{
	httpAsync('POST', url, data, callback);
}

function readAsync(url, callback)
{
	httpAsync('GET', url, null, callback);
}

function updateAsync(url, data, callback)
{
	httpAsync('PUT', url, data, callback);
}

function deleteAsync(url, callback)
{
	httpAsync('DELETE', url, null, callback);
}
