'use strict';

var _callback;
var _authorized;
var _audience;

function browseridInit(callback, authorized, audience)
{
	_callback = callback;
	_authorized = authorized;
	_audience = audience;
	loadScript('https://login.persona.org/include.js');
}

function browseridCreateButton()
{
	var img = document.createElement('img');
	img.src = 'includes/browserid.png';
	img.alt = 'Sign in';

	var a = document.createElement('a');
	a.href = '.';
	a.title = 'Sign in with BrowserID';
	a.appendChild(img);
	a.onclick = function()
	{
		navigator.id.get(browseridAssertion);
		return false;
	};

	return a;
}

function browseridAssertion(assertion)
{
	if (assertion !== null)
	{
		var data = 'authorized=' + _authorized + '&audience=' + _audience + '&assertion=' + assertion;
		createAsync('includes/browserid.php', data, function(x)
		{
			_callback(JSON.parse(x));
		});
	}
}
