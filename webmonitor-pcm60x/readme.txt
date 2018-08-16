
![alt text](https://raw.githubusercontent.com/solarsnoop/PCM60X-Monitor/master/webmonitor-pcm60x/pcm60xmonitor.jpg)

To use this script just call into your browser is connected to the same LAN as your raspberry pi:

http://ipadressofyourraspberrypi/solar/pcm60x.php

this is still under construction. finaly i will create a web based administration menue,
where you can watch your current settings and parameters. Also update some settings easy by using this website.
The project is testing with a raspberry pi 3.
phyton2.7 and php5 compatible codes
folders:
/var/www/html/solar/ -> php scrits
/home/pi/pyhton/ -> pyhton scripts and txt files

you have to add this line into /etc/sudooer ->
www-data ALL=(root) NOPASSWD: /usr/bin/python

additional you need to have the rasberry pi user www-data (If not exist)
sudo adduser $USER www-data
sudo chgrp -R www-data /var/www
sudo chmod -R g+w /var/www
sudo chmod g+s /var/www

Afer this just copy the php files in the folder: /var/www/html/solar/ -> php scrits
and the py and txt files into: /home/pi/pyhton/ -> pyhton scripts

The txt files just need existing , no need have any content at start. At the moment i use the files for transfer the datas between pyhton and php

Attention, the srcipt take some seconds because the interaction between webbrowser - raspberry pi  and pcm60x. The status is only a snapshout of a moment. If you need nearly real time monitoring, you should use emoncms or such like this solution. The work arround how to send datas to emoncms, you will find in the main folder of this project. please read the README FILE
