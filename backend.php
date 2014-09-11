<?php
$sec = "SECRET PHRASE HERE";
$msg = "Hello and welcome to NXT, now that your account is initialized, you have a greater level of security in the system. To obtain NXT you can buy them from places such as MGW or BTER, or you can check in with the community at nxtforum.org.\nI hope you enjoy your time here,\n -- Jones";

function query($type, $attr)
{
	return json_decode(file_get_contents("http://127.0.0.1:7876/nxt?requestType=".$type."&".$attr));
}


// gives money to people who ask

$nxt = $_POST['nxt'];
$email = $_POST['email'];
$pub = $_POST['pub'];
$myip = $_SERVER['REMOTE_ADDR'];

//check against previous trans
$table = json_decode(file_get_contents("table.json"));

$addrs = $table->email;

$has = query("getAccountPublicKey", "account=".nxt);
if(isset($has->publicKey))
{
	echo "this account already has a public key";
	die;
}
$conn = query("getAccountId", "publicKey=".$pub);
if($conn->accountRS != $nxt)
{
	echo "your NXT account and public key do not match up";
	die;
}

for($i = count($addrs)-1; $i >= 0; $i--)
{
	if($nxt == $addrs[$i])
	{
		echo "This email has been used previously.";
		die;
	}
}
$ips = $table->ip;
for($i = count($ips)-1; $i >= 0; $i--)
{
	if($myip == $ips[$i])
	{
		echo "already have an account initialized from that IP";
		die
	}
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://127.0.0.1:7876/nxt");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "requestType=sendMessage&recipient=".$addr."&feeNQT=10000000&deadline=1440&secretPhrase=".$sec."&message=".$msg."&recipentPublicKey=".$pub);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec ($ch);

curl_close ($ch);

array_push($table->ip,$myip);
	array_push($table->address, $nxt);
	array_push($table->email, $email);
	array_push($table->accessed, time());
	file_put_contents("table.json", json_encode($table));

echo "<script>window.location = 'index.php?success."';</script>";

?>
