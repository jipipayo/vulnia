#!/bin/sh

cd /opt/pentaho/data-integration/

sh kitchen.sh -file="/home/dani/workspace/vulnia/kettle/nvdcve/Main_nvdcve.kjb"  -level=Detailed > /home/dani/workspace/vulnia/kettle/nvdcve/tmp/lastExecution.log

/usr/local/bin/indexer --all --rotate

exit 0
