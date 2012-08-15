<?php

require $_POST['authorized'];

$valid = false;
$url = 'https://verifier.login.persona.org/verify';
$assertion = $_POST['assertion'];
$audience = urlencode($_POST['audience']);
$params = "assertion=$assertion&audience=$audience";
$ch = curl_init();

$options = array
(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 2,
	CURLOPT_POSTFIELDS => $params
);

curl_setopt_array($ch, $options);
$result = curl_exec($ch);
$json = json_decode($result);

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

if (!$valid)
{
	deauthorize();
}

echo json_encode($json);
