
![alt text](https://raw.githubusercontent.com/solarsnoop/PCM60X-Monitor/master/pcm60xmonitor.jpg)

To use this script just call into your browser is connected to the same LAN as your raspberry pi:

http://ipadressofyourraspberrypi/monitor.php

**Attention: the Monitor is only working in the day time when the Display of the PCM60X is online!!!**

this project is still under construction. finaly i will create a web based administration menue,
where you can watch your current settings and parameters. Also update some settings easy by using this website.
The project is testing with a raspberry pi 3.
**phyton2.7 and php5 compatible codes**
folders:
/var/www/html/ -> copy all in this folder or create a new subfolder

additional you need to have the rasberry pi user www-data (If not exist)
sudo adduser $USER www-data
sudo chgrp -R www-data /var/www
sudo chmod -R g+w /var/www
sudo chmod g+s /var/www

Afer this just copy the php files in the folder: /var/www/html/
all files should be own of www-data user (or similar user for running scripts in this file

The txt files just need existing , no need have any content at the beginning. At the moment i use the files for transfer the datas between pyhton and php

Attention, the srcipt take some seconds because the interaction between webbrowser - raspberry pi  and pcm60x. The status is only a snapshout of a moment. If you need nearly real time monitoring, you should use emoncms or such like this solution. The work arround how to send datas to emoncms, you will find in the main folder of this project. please read the README FILE
