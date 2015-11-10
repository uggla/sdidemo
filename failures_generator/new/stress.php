<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="René Ribaud" >
    <link rel="icon" href="images/favicon.ico">
    <meta name="description" content="Generate a failure">
    <meta name="author" content="René Ribaud">
    <meta http-equiv="refresh" content="3; URL=http://10.3.8.29/failures_generator/new">

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

		<img class="navbar-brand navbar-brand-img" alt="HP" src="images/hpelogo.png"/>

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
<?php
if ($_POST["btn"] == "Stop Stress"){
	if ($_POST["system"] == ""){
		print "Stopping Stress on system ".$_POST["ip"]."<br/>\n";
	}
	else{
		print "Stopping stress on system ".$_POST["system"]."<br/>\n";
	}
		$cmd = 'pkill -f '. $_POST["ip"] .'/[p]restashop';


	shell_exec($cmd);

	sleep(1);

	$process = shell_exec('ps -eo pid,pcpu,stat,comm  | egrep "PID|siege"');
	$process = explode("\n", trim($process));

	print "<br/>\n";
	print "<table>\n";
	foreach($process as $proc)
	{
		$proc = preg_split("/\s+/", $proc);
		printf("<tr>\n");
		printf("<td>%s</td>\n",$proc[0]);
		printf("<td>%s</td>\n",$proc[1]);
		printf("<td>%s</td>\n",$proc[2]);
		printf("<td>%s</td>\n",$proc[3]);
		printf("</tr>\n");

	}
		print "</table>";

}
else{
	if ($_POST["system"] == ""){
		print "Stressing system ".$_POST["ip"]."<br/>\n";
	}
	else{
		print "Stressing system ".$_POST["system"]."<br/>\n";
	}
		$cmd = 'siege -d1 -r1000 -c500 http://' . $_POST["ip"] . '/prestashop >/dev/null &';


	shell_exec($cmd);

	sleep(2);

	$process = shell_exec('ps -eo pid,pcpu,stat,comm  | egrep "PID|siege"');
	$process = explode("\n", trim($process));

	print "<br/>\n";
	print "<table>\n";
	foreach($process as $proc)
	{
		$proc = preg_split("/\s+/", $proc);
		printf("<tr>\n");
		printf("<td>%s</td>\n",$proc[0]);
		printf("<td>%s</td>\n",$proc[1]);
		printf("<td>%s</td>\n",$proc[2]);
		printf("<td>%s</td>\n",$proc[3]);
		printf("</tr>\n");

	}
		print "</table>";
}
?>
</div>
</div>
</body>
</html>
