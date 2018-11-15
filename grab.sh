#!/bin/bash

username="CarlGarner"
rundate=$(date +"%m-%d-%Y")

lynx -cfg=/root/libre-grafana/lynx.cfg -cmd_script=/root/libre-grafana/commands https://www1.libreview.com/Accounts/Login?lang=en > /dev/null
sed -i '1d' LV_${username}_Export_${rundate}.csv

php -f parse.php LV_${username}_Export_${rundate}.csv
