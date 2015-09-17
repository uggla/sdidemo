<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="René Ribaud" >
    <link rel="icon" href="images/favicon.ico">
    <meta http-equiv="refresh" content="10"> 
    <meta name="description" content="Generate a failure">
    <meta name="author" content="René Ribaud">
    <meta http-equiv="refresh" content="5" url="http://10.3.8.29/failures_generator">

    <title>Failure Generator</title>

    <!-- Bootstrap core CSS -->
    <!--<link href="css/bootstrap.css" rel="stylesheet">-->
	<link href="css/cosmo.bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/demoportal.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <style>
 .link {
  stroke: #999;
  stroke-opacity: .6;
}

  </style>

  <body>


    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">

		<img class="navbar-brand navbar-brand-img" alt="HP" src="images/hpwhite.png"/>

		  <div class="navbar-brand">
		  Failure Generator
		  </div>

	  <div class="container">

		<div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

			<!--<img class="navbar-brand navbar-brand-img" alt="HP" src="images/hpwhite.png"/>

		  <div class="navbar-brand">
		  Cluster UI
		  </div>-->

        </div>

		<div class="collapse navbar-collapse">
          <ul id="tabsNavBar" class="nav navbar-nav">
            <li id="clusterTabButton" class="navbar-nav-li active"><a target="_blank" href="#dycl" data-toggle="tab">Home</a></li>
			<!--<li class="navbar-nav-li"><a target="_blank" href="#docs" data-toggle="tab">Documents</a></li>-->
          </ul>
       <!-- </div>
        <div class="navbar-collapse collapse">-->
			<!--<form class="navbar-form navbar-right" role="form">
				<div class="form-group">
					<input id="host" placeholder="Hostname" class="form-control" type="text">
				</div>
				<div class="form-group">
					<input id="port" placeholder="Port" class="form-control" type="text">
				</div>
			</form>-->
        </div><!--/.navbar-collapse -->
      </div>
    </div>


    <div class="container">
        <div class="row col-xs-12">
            <h2>Click the following buttons to generate a failure</h2>
            <hr>
       </div>
        <div class="row">
 <!--           <div class="col-lg-6">
                            <div class="thumbnail right-caption span4">
                               <div class="img-block"><img src="./img/01a-1-History.png" alt=""></div>
                              <div class="caption">
                                <h5>Tweets history</h5>
                                                <p class="desc">An historical view of tweets collected each day in the platform</p>
                                <p><a target="_blank" href="http://10.3.220.50/views/HavenLiveDemos-01a-GlobalTweets/1-History?:embed=y" class="btn btn-primary">Demo</a>
<a target="_blank" href="http://10.3.220.51/views/HavenLiveDemos-01a-GlobalTweets/1-History?:embed=y" class="btn">Backup link</a> </p>
                              </div>
                            </div>
						</div>-->

<?php
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
				$web_servers[$value]["hostname"] = $service[$hostkey];
				$web_servers[$value]["lastupdate"] = $service["lastupdate"];
				$pubip = explode('-',$key);
				$pubip = $pubip[0];
				$web_servers[$value]["pubip"] = $service[$pubip];
				}
			else{
				$web_servers[$value] = "";
				$web_servers[$value]["lastupdate"] = $service["lastupdate"];
			}
		}
	}
}

#print var_dump($web_servers);
# Now print the data
foreach($web_servers as $ip => $values){
    printf('<div class="col-lg-6">');
    printf('<div class="thumbnail right-caption span4">');
    printf('<div class="img-block"><img src="images/moonshot.png" alt="" style="width:150px"></div>');
    printf('<div class="caption">');
    printf('<h5>%s</h5>', $values["hostname"]);
    printf('<p class="desc">%s / %s</p>', $ip, $values["pubip"]);
    printf('<p class="desc">Deployed %s</p>', $values["lastupdate"]);
    printf('<form action="stress.php" method="POST">');
	printf("<input type=\"hidden\" name=\"ip\" value=\"%s\">\n",$ip);
	printf("<input type=\"hidden\" name=\"system\" value=\"%s\">\n",$values["hostname"]);
	$cmd = 'pgrep -f '. $ip .'/[p]restashop';
	$output = shell_exec($cmd);
	//print $output;
	if ($output == ""){
		printf('<input class="btn btn-primary" type="submit" name="btn" value="Stress">');
		printf('<input class="btn" type="submit" name="btn" value="Stop Stress"></form>');
	}
	else{
		printf('<input class="btn" type="submit" name="btn" value="Stress">');
		printf('<input class="btn btn-primary" type="submit" name="btn" value="Stop Stress"></form>');

	}
	printf('<br/>');
    printf('<form action="stop.php" method="POST">');
	printf("<input type=\"hidden\" name=\"ip\" value=\"%s\">\n",$ip);
	printf("<input type=\"hidden\" name=\"system\" value=\"%s\">\n",$values["hostname"]);
	$cmd = '/usr/bin/ssh -i id_rsa -o StrictHostKeyChecking=no root@'. $ip. ' /etc/init.d/apache2 status';
	$output = shell_exec($cmd);
	//print $output;
	if (preg_match('/apache2 is not running/', $output)){
		printf('<input class="btn" type="submit" name="btn" value="Stop web service">');
		printf('<input class="btn btn-primary" type="submit" name="btn" value="Restart web service"></form>');
	}
	else{
		printf('<input class="btn btn-primary" type="submit" name="btn" value="Stop web service">');
		printf('<input class="btn" type="submit" name="btn" value="Restart web service"></form>');
	}
    printf('</div>');
    printf('</div>');
    printf('</div>');
}

?>
	 </body>
    </html>
