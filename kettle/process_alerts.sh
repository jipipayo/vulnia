#!/bin/sh

cd /opt/pentaho/data-integration/

sh kitchen.sh -file="/home/dani/workspace/vulnia/kettle/process_alerts.kjb"  -level=Detailed > /home/dani/workspace/vulnia/kettle/tmp/lastExecution.log


exit 0
