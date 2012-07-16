<?php

require $_POST['authorized'];

$valid = false;
$url = 'https://browserid.org/verify';
$data = http_build_query
(
	array
	(
		'audience' => urlencode($_POST['audience']),
		'assertion' => $_POST['assertion']
	)
);

$params = array
(
	'http' => array
	(
		'method' => 'POST',
		'header' => 'Content-Type: application/x-www-form-urlencoded',
		'content' => $data
	)
);

$ctx = stream_context_create($params);
$fp = fopen($url, 'rb', false, $ctx);

if ($fp)
{
	$json = json_decode(stream_get_contents($fp));

	if ($json->status == 'okay')
	{
		$email = $json->email;
		$userid = authorize($email);

		if ($userid > 0)
		{
			$valid = true;
			$json->userid = $userid;
			$json->authorized = true;
		}
		else
		{
			$json->authorized = false;
		}
	}
}

if (!$valid)
{
	deauthorize();
}

echo json_encode($json);

?>
