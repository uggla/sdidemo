"""
    Written by Paul Tardy - July 2015
    Generating Demoportal index.html from a CSV file describing demo/usecase

"""

import collections
__author__ = 'Paul Tardy'

import csv


def html_demosection(title):
    return ""


htmlFilePath = "index.html"
htmlFile = open(htmlFilePath, "w", newline='')

csvFilePath = "demo-portal-items.csv"
csvFile = open(csvFilePath, "r", newline='')
delimiter = ";"

csvReader = csv.reader(csvFile, delimiter=";")
firstrow = True
demosection = {}

html = ""
for row in csvReader:
    if firstrow:
        firstrow = False
        continue
    # print(row)

    demo = row[0]
    page_id = row[1]
    title = row[2]
    desc = row[3]
    prim_url = row[4]
    sec_url = row[5]
    img = row[6]
    status = row[7]
    if status != "PROD":
        continue
    if not demo in demosection:
        #print("Nouvelle section %s" % demo)
        demosection[demo] = ""

    print("Demo: ", demo, " Usecase: ", title)
    sec_url_html = "\n<a target=\"_blank\" href=\"" + sec_url + "\" class=\"btn\">Backup link</a>" if (sec_url != "none" and sec_url != "") else ""
    demosection[demo] += """<div class="col-lg-6">
                            <div class="thumbnail right-caption span4">
                               <div class="img-block"><img src=\"./img/""" + img + """\" alt=""></div>
                              <div class="caption">
                                <h5>""" + title + """</h5>
                                                <p class="desc">""" + desc + """</p>
                                <p><a target="_blank" href=\"""" + prim_url + """\" class="btn btn-primary">Demo</a>""" + sec_url_html + """ </p>
                              </div>
                            </div>
                            </div>"""

        # print(demosection[demo])
csvFile.close()
html = """<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <title>Demo Portal</title>

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
		  Demo Portal
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


    <div class="container">"""
odemosection = collections.OrderedDict(sorted(demosection.items()))
for demo, val in odemosection.items():
    #print(demo)
    html += """
        <div class="row col-xs-12">
            <h2>Demo """ + demo + """</h2>
            <hr>
       </div>
        <div class="row">
            """ + val + """
        </div>
        """

html += """
    </div>
        </div><!-- /.container -->
    </body>
    </html>"""
htmlFile.write(html)
htmlFile.close()
