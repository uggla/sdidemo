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

<?php
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
print "</table>"
?>

</body>
</html>
