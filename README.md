This guide assumes you are running InfluxDB/Grafana on the same box.

You will also need PHP (>7), the Lynx www-text browser installed and to be comfortable with the Linux command line.

Create your InfluxDB database with:

curl -i -XPOST http://localhost:8086/query --data-urlencode "q=CREATE DATABASE robocarl"

You'll need to replace any references to "robocarl" with the database name you chose.

The following allows you to record the keypress process required to log in to the Libre website, allowing
the script to run without further interaction.

lynx -cfg=/root/grafana-libre/lynx.cfg -cmd_log=/root/grafana-libre/commands https://www1.libreview.com/Accounts/Login?lang=en

Once you have logged in, press "g" in Lynx and paste the URL below to initiate the CSV download:

https://www1.libreview.com/Dashboard/HomeUserExportPatientData

Ensure you save the file to disk and then press "q" to initiate the exit process of Lynx. Failure to complete all actions
will leave you with a partial command script which will block the process from completing.

I run the download every 15 minutes, more frequently is up to you, but as data points are every 15 minutes, it doesn't make sense to make it less than this.

15 * * * * cd /root/libre-grafana && ./grab.sh

Import the json template to your Grafana instance to view the graphed data. Bandings are set according to my requirements, feel free to modify to suit

