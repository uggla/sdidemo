<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Generate a failure</title>
  <meta name="description" content="Generate a failure">
  <meta name="author" content="René Ribaud">

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/hp.css">

</head>

<body>
<?php include "header.html"; ?>


Click the following buttons to generate a failure.
<br/>
<br/>
<?php
#$file = "/home/sdi/ansible/essai/datafile.json";
$file = "datafile.json";

$json = file_get_contents($file);
if($json == False){
	print "Failed to open json file";
	exit(1);
}


$data = json_decode($json, true);


# Decode json and get web servers IP / Hostname
$web_servers = array();
foreach($data as $service){

	foreach($service as $key=>$value){
		if( preg_match("/web\d-client$/", $key, $matches)){
			$hostkey = $key . "-hostname";
			if(isset($service[$hostkey])){
				$web_servers[$value] = $service[$hostkey];
				}
			else{
				$web_servers[$value] = "";
			}
		}
	}

}

#print var_dump($web_servers);
# Now print the data
foreach($web_servers as $ip => $hostname){
	printf("<h2>Server : %s (%s)</h2>\n", $ip, $hostname);
	printf("<img src=\"images/moonshot.png\" style=\"width:150px\">\n");
	printf("<br/>\n");
	printf("<br/>\n");
	printf("<form action=\"stress.php\" method=\"POST\">\n");
	printf("<input type=\"hidden\" name=\"ip\" value=\"%s\">\n",$ip);
	printf("<input type=\"hidden\" name=\"system\" value=\"%s\">\n",$hostname);
	printf("<input type=\"submit\" value=\"Stress\">\n");
	printf("</form>\n");
	printf("<br/>\n");
	printf("<form action=\"stop.php\" method=\"POST\">\n");
	printf("<input type=\"hidden\" name=\"ip\" value=\"%s\">\n",$ip);
	printf("<input type=\"hidden\" name=\"system\" value=\"%s\">\n",$hostname);
	printf("<input type=\"submit\" value=\"Stop web service\">\n");
	printf("</form>\n");
	printf("<br/>\n");
	printf("<br/>\n");
}

?>
</body>
