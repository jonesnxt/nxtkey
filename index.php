<?php 
$account = "NXT-...";

	function timeago($timestamp)
	{

		$fromnow =  time() - $timestamp;
		
		$days =  floor($fromnow/86400);
		$hours = floor(($fromnow%86400)/3600);
		$minutes = floor(($fromnow%3600)/60);
		$seconds = floor($fromnow&60);
		$acc = "";
		if($days != 0 && $days != 1) $acc = $days . " days ago";
		else if($days == 1) $acc = " 1 day ago";
		else if($hours != 0 && $hours != 1) $acc = $hours . " hours ago";
		else if($hours == 1) $acc = "1 hour ago";
		else if($minutes != 0 && $minutes != 1) $acc = $minutes . " minutes ago";
		else if($minutes == 1) $acc = "1 minute ago";
		else if($seconds != 0 && $seconds != 1) $acc = $seconds . " seconds ago";
		else if($seconds == 1) $acc = "1 second ago";
		else $acc = "just now";
		
		return $acc;
	}


?>
<!DOCTYPE html>
<html>
<head>
<title>Initialize NXT Account</title>
<link href="style.css" rel="stylesheet"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="../jquery.js"></script>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"/>
<script>
$().ready(function() {
	$("#nxt").val("<?php if(isset($_GET["nxt"])) echo $_GET['nxt']; else echo ""; ?>");
	$("#pub").val("<?php if(isset($_GET["pub"])) echo $_GET['pub']; else echo ""; ?>");
});
</script>
</head>
<body>
<div class="container container-fluid" role="main">
	<?php include_once("../topbar.php"); ?>
		
		<div class="page-header"><h2>Initialize NXT Account</h2></div>
		<div class="row"><div class="form-group col-md-6">
			<div class="panel panel-default">
			<div class="panel-heading"><h3><div class="glyphicon glyphicon-thumbs-up"></div> Announce your Public Key</h3></div>
			<div class="panel-body">
				<div class="alert alert-info text-center"><strong>Initializing your NXT account by announcing your account public key to the network. Doing this adds a greater level of security to your coins once you obtain them. After Doing this all you have to supply to recieve money is you RS address (NXT-XXXX-XXXX-XXXX-XXXXX). </strong></div>
<br/><form method="post" action="backend.php">
	<div class="form-group">
<label for=nxt>NXT Address: </label><br/><input type="text" id="nxt" name="nxt" class="form-control"/><br/>
<label for=pub>Public Key: </label><br/><input type="text" id="pub" name="pub" class="form-control"/><br/>
<label for=email>Email Address: </label><br/><input type="text" name="email" class="form-control"/><br/>

<input type="submit" value="Initialize" class="btn btn-lg btn-primary"/>
</div>
<div class="bs-callout bs-callout-info">
<?php
function query($type, $attr)
{
	return json_decode(file_get_contents("http://127.0.0.1:7876/nxt?requestType=".$type."&".$attr));
}

$acc = query("getAccount", "account=".$account);
echo "Address has ".($acc->balanceNQT/100000000)." NXT";
?>
<br/>
Help others start with NXT and donate to <strong><? echo $account; ?></strong><br/>
thank you (:
</div>

</form>
</div></div></div>
		<div class="form-group col-md-6">
			<div class="panel panel-default">
			<div class="panel-heading"><h3><div class="glyphicon glyphicon-globe"></div> Recently Initialized</h3></div>
			<div class="panel-body">

<table class="table table-striped table-hover">
<thead>
<td>address</td><td>time</td>
</thead>
<?php 
	$json = file_get_contents("table.json");
	$rows = json_decode($json);
	$cnt = count($rows->{"address"})-1;
	while($cnt >= count($rows->address)-11)
	{
		echo "<tr>";
		echo "<td>".$rows->{"address"}[$cnt]."</td>";
		echo "<td>".timeago($rows->{"accessed"}[$cnt])."</td>";
		echo "</tr>";

		$cnt --;
	}
?>
</table></div></div></div>
		
	<?php include_once("../closing.php") ?>
</div>
</body>
</html>
