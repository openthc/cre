#!/bin/bash -x
#
# Test our API
#

set -o errexit
set -o nounset

f=$(readlink -f "$0")
d=$(dirname "$f")

cd "$d"

dt=$(date)
dts=$(date +%Y%m%d-%H%M%S)

out_path="../webroot/test-output"
if [ ! -d "$out_path" ]
then
	mkdir "$out_path"
fi


#
#
../vendor/bin/phpunit "$@" 2>&1 |tee "$out_path/output.txt"

if [ ! -f "phpunit-report.xsl" ]
then
	wget https://openthc.com/css/phpunit-report.xsl
fi

xsltproc \
	--nomkdir \
	--output "$out_path/output.html" \
	phpunit-report.xsl \
	"$out_path/output.xml"

note=$(tail -n1 "$out_path/output.txt")


cat > "$out_path/index.html" <<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="initial-scale=1, user-scalable=yes">
<meta name="application-name" content="OpenTHC">
<meta name="apple-mobile-web-app-title" content="OpenTHC">
<meta name="msapplication-TileColor" content="#247420">
<meta name="theme-color" content="#247420">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />
<title>Test Result</title>
</head>
<body>
<div class="container">
<div class="jumbotron mt-4">

<h1>Test Results ${dt}</h1>
<h2>${note}</h2>

<p>You can view the <a href="output.txt">raw script output</a>,
or the <a href="output.xml">Unit Test XML</a>
which we've processed <small>(via XSL)</small> to <a href="output.html">a pretty report</a>
which is also in <a href="testdox.html">testdox format</a>.
</p>

<!-- <p>Originally Published at <a href="/test-output-${dts}/">/test-output-${dts}/</a> -->

</div>
</div>
</body>
</html>
HTML

#
# Archive
# rsync -av "$out_path/" "$out_path-$dts/"
