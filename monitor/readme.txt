
To use the pcm60x-monitor, just call into your browser is connected to the same LAN as your raspberry pi:

http://ipadressofyourraspberrypi/monitor/

**Attention: the Monitor is only working in the day time when the Display of the PCM60X is online!!!**

this project is still under construction. finaly i will create a web based administration menue,
where you can watch your current settings and parameters. Also update some settings easy by using this website.
The project is testing with a raspberry pi 3.

add in /bin/sudoers ->
nano /bin/sudoers
add this line at the end
www-data ALL=(root) NOPASSWD: /usr/bin/python

**phyton2.7 and php5 compatible codes**
folders:
/var/www/html/ -> copy all in this folder or create a new subfolder

additional you need to have the rasberry pi user www-data (If not exist)
sudo adduser $USER www-data
sudo chgrp -R www-data /var/www
sudo chmod -R g+w /var/www
sudo chmod g+s /var/www

Afer this just copy the php files into the folder: /var/www/html/
all files should be own by www-data user

The txt files only need existing , and the not need to have any content with creating. At the moment i use the files for transfer the datas between pyhton and php

Attention, the srcipt take some seconds because the interaction between webbrowser - raspberry pi  and pcm60x. The status is only a snapshout of a moment. If you need nearly real time monitoring, you should use emoncms or such like this solution. The work arround how to send datas to emoncms, you will find in the main folder of this project. please read the README FILE
