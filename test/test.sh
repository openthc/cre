#!/bin/bash
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
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, user-scalable=yes">
<meta name="theme-color" content="#247420">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.openthc.com/bootstrap/4.4.1/bootstrap.css" integrity="sha256-L/W5Wfqfa0sdBNIKN9cG6QA5F2qx4qICmU2VgLruv9Y=" crossorigin="anonymous" />
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
