# PCM60X-Monitor:

###################################################################################

Demo site:  https://emoncms.de/CRC/

###################################################################################

**PCM60X Solar Charger Web Based Monitor based on php and python**

You need:
- Raspberry PI 2 or 3
- Rasbian Jessie or Stretch -> apache, php 5 and python 2.7
- USB Serial adapter with PL2303 (USB0*) or a serial adapter use the GPIOS (AMA0)
  (recommendation UGREEN USB to RS232 Seriell PL2303)
- PCM60X Solar Charger

*Use **sudo dmesg** for see what serial port your adapter is useing.

**example USB0: ttyUSB0 or 1 ...:**
```
please change the CODE in the two files:
sendcode.py
refresh.py
```
**and look to find:**
```
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
change this code to 
ser = serial.Serial(port='/dev/ttyUSB1',baudrate=2400,timeout=2)
```
(This is only an example when you are not using the USB0 Port)

For the PCM60X Web Monitor it easy to use in your local network, just call in yor browser: 

http://ip-raspberryPi/monitor/

Chrome/Firefox is tested

![alt text](https://raw.githubusercontent.com/solarsnoop/PCM60X-Monitor/master/pcm60xmonitor.jpg)

**source for codes:**
THANKS to https://github.com/njfaria (help me for understand CRC code and gives me special information about some crc handling)

**source for the crc.php pased on this forum:**  
https://www.szenebox.org/archive/index.php/t-4319.html user NIMBUS

**Project:**

monitor and administration pcm60x charger via web client.

**You need to do:**
```
cd ..
cd var
cd www
cd html

git clone https://github.com/solarsnoop/PCM60X-Monitor.git

sudo mv /var/www/html/PCM60X-Monitor/monitor /var/www/html/monitor/
sudo rm -rf PCM60X-Monitor/
```
**add in /etc/sudoers ->**
nano /etc/sudoers -> add this line at the end
```
www-data ALL=(ALL) NOPASSWD: /usr/bin/
```

**phyton2.7 and php5 compatible codes**
folders:
/var/www/html/monitor/ -> copy all files in this folder or create a other subfolder, but than you have to modify the codes!

**additional you need to have the rasberry pi user www-data (If not exist)**
```
sudo adduser $USER www-data
sudo chgrp -R www-data /var/www
sudo chmod -R g+w /var/www
sudo chmod g+s /var/www
```
**add the follow cron jobs to your root tab:**
using: sudo nano /var/spool/cron/crontabs/root 
```
* * * * * python2 /var/www/html/monitor/refresh.py
* * * * * sleep  15; python2  /var/www/html/monitor/refresh.py
* * * * * sleep  30; python2 /var/www/html/monitor/refresh.py
* * * * * sleep  45; python2  /var/www/html/monitor/refresh.py
```
after that call in your browser http://ipadressraspberrypi/monitor/

**in the folder https://github.com/solarsnoop/PCM60X-Monitor/monitor:**
you will find the project for the web client monitor

**in this folder here in github/PCM60X-Monitor:**
you find some work arrounds.

Simple workaround for send recive datas to your PCM60X solar charger
You can send codes to your PCM60x Charger, using the description of Solar Charge Controler - PCM60X - RS232 Protocol.pdf.

What you need to do is connect your raspberry pi with the existing RS232 cable, and buy an adapter to USB (pl2303 chip) or use an serielle interface adapter. 



** some examples for testing **


In the codes just modify the interface, depending what you are using. eg.: AMA0 (Seriell adapter) or USB0 to 1,2,3, depending what usb port your adapter use this code you need to modify:
```
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
```
2nd. better option is:
1. open the shell window and call: ls -l /dev/serial/by-id
find the adapter 
![alt text](https://raw.githubusercontent.com/solarsnoop/PCM60X-Monitor/master/serport.jpg)
In this example it the: usb-Prolific_Technology_Inc._USB-Serial_Controller-if00-port0

Please copy your result for the Prolific controler and use it in the same way like this:
ser = serial.Serial(port='/dev/serial/by-id/usb-Prolific_Technology_Inc._USB-Serial_Controller-if00-port0',baudrate=2400,timeout=2) 
if you use this sytax and not the ttyUSBx Syntex, you will not have the Problem with USB Port switching , after reboot from the PI. 

ATTENTION ALL CODE HAVE THE FIX SYNTEX LIKE:
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
please modifiy your Code depending on your port list:
ser = serial.Serial(port='/dev/serial/by-id/usb-Prolific_Technology_Inc._USB-Serial_Controller-if00-port0',baudrate=2400,timeout=2) 

--------------------------------------------------------------------------------------------------------------------------------------------

I will show you 2 example how you can read datas and send datas to the PCM60x , and how you can post it to any aplication in this case emoncms (guithub project) and website at http://emoncms.org.

1. going to the website:
https://emoncms.de/CRC/crc.php

This website calculate the hex code and crc code what you have to send to the charger, its a polynom function: x16 + x12 + x5 + 1
0x1021 CRC-CCITT (XModem)

2. now use the code you want test, in this example we use the code: "QPIGS"
->
```
Computer: QPIGS <CRC><cr>
Device: (BBB.B CC.CC DD.DD EE.EE FF.FF GGGG ±HHH II.II ±JJJ KKKK
b7b6b5b4b3b2b1b0 <CRC><cr>
```

It means you send the QPIGS code to your charger follow by CRC code and return code
The website is an translator for generate the code. You just fill in "QPIGS" the rest makes the website.

in our case we get this result:
```
CRC-CCITT (XModem) -> QPIRI:	Send code to PIP: \x51\x50\x49\x52\x49\xF8\x54\x0D
```

So now the python2 code:

```
import time
import urllib
import serial
QCODE = "\x51\x50\x49\x52\x49\xF8\x54\x0D"
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
print QCODE
ser.write(QCODE)
result = ser.read(70)
print result
ser.close()
```

save this code in your home/pi/python folder under send.py
than open a command window 
-> now run this command:
```
python2 /home/pi/pyhton/send.py
```
now you should see similar like this:

```
QPIRI�T
(3000 24 60.0 14.60 13.85 02 01 +00.0 00 00 2 11.50 0)�
3000 Watt
24V system
60 A max
bulk 14.60 (x2 because 24V)
float 13.85 (x2 because 24V)
02 (00: AGM, 01: Flooded, 02: Customized)
01 (00: Remote battery sensing disable, 01: Remote battery sensing enable)
```

the rest is :
```
I ±II.I Battery Temperature, Compensation I is an Integer ranging from 0 to 9.The unit is mV.
J JJ Remote Temperature Detect. J is an Integer ranging from 0 to 9.
00: Remote temperature sensing
disable
01: Remote temperature sensing
enable
K KK Battery rated voltage set
00: Enable battery voltage auto
sensing
```
If you want send your datas to emoncms you can use this code and call it in a cronejob every 20 seconds:
(example for QPIGS)
```
import time
import urllib
import serial
import os, ssl
if (not os.environ.get('PYTHONHTTPSVERIFY', '') and
    getattr(ssl, '_create_unverified_context', None)): 
    ssl._create_default_https_context = ssl._create_unverified_context
QPIGS = "\x51\x50\x49\x47\x53\xB7\xA9\x0D"
apikey="youremoncmsapiwritekey"
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
ser.write(QPIGS)
result = ser.read(70)
ser.read()
ser.read()
print result
url="https://yourexternalurlforemoncms/input/post?json={chargerwatt:"
url+=result [31:35]
url+=",battchargervolt:"
url+=result [41:46]
url+=",pvchargervolt:"
url+=result [1:6]
url+=",chargertempa:"
url+=result [38:40]
url+=",battchargervolt1:"
url+=result [7:12]
url+="}&node=pcm60x&apikey="
url+=apikey
print url
content = urllib.urlopen(url).read()
ser.close()
```

FOR CHANGE SETTINGS OF YOUR CHARGER EG FLOATING VOLT SEE THE NEXT EXAMPLE:

We want change floating settings to 13.50 Volt (or 27V or 54V) Inj every case you send the 12V nominal value.
The qcode for floating voltage is:
```
PBFV< FF.FF><cr>: Setting battery floating charging voltage
Computer: PBFV<FF.FF><CRC><cr>
Device: (ACK<CRC><cr> if device accepted, or respond (NAK<CRC><cr>
```
So we use the website https://emoncms.de/CRC/crc.php
PBFV13.50: PBFV13.50:	Send code to PIP: \x50\x42\x46\x56\x31\x33\x2E\x35\x30\xB4\x93\x0D

CODE:
So now the python2 code:
```
import time
import urllib
import serial
QCODE = "\x50\x42\x46\x56\x31\x33\x2E\x35\x30\xB4\x93\x0D"
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
print QCODE
ser.write(QCODE)
result = ser.read(70)
print result
ser.close()
```

save this code in your home/pi/python folder under setfloat.py
than open a command window 
-> now run this command:
```
python2 /home/pi/pyhton/setfloat.py
```
After this you will see :
```
PBFV13.50��
(ACK9 
So after that your floating setup changed to 13.50 (x1,2 or4)
```
**New code for MQTT (eg IOBROKER, mosquitto MQTT Broker, ...) example**
```
import time
import serial
import paho.mqtt.publish as publish
import os, ssl
QPIGS = "\x51\x50\x49\x47\x53\xB7\xA9\x0D"
MQTT_SERVER = "127.0.0.1"
MQTT_PORT = 1883
MQTT_PATH1 = "solpiplog/PCM60x/watt"
MQTT_PATH2 = "solpiplog/PCM60x/strom"
MQTT_PATH3 = "solpiplog/PCM60x/voltpv"
MQTT_PATH4 = "solpiplog/PCM60x/temp"
MQTT_PATH5 = "solpiplog/PCM60/voltb"
ser = serial.Serial(port='/dev/serial/by-id/usb-Prolific_Technology_Inc._USB-Serial_Controller-if00-port0',baudrate=2400,timeout=2)
if serial.serialutil.SerialException and ser.isOpen():
   ser.write(QPIGS)
   result = ser.read(68)
publish.single(MQTT_PATH1, result [31:35], hostname=MQTT_SERVER, port=MQTT_PORT)
publish.single(MQTT_PATH2, result [14:19], hostname=MQTT_SERVER, port=MQTT_PORT)
publish.single(MQTT_PATH3, result [1:6], hostname=MQTT_SERVER, port=MQTT_PORT)
publish.single(MQTT_PATH4, result [38:40], hostname=MQTT_SERVER, port=MQTT_PORT)
publish.single(MQTT_PATH5, result [7:12], hostname=MQTT_SERVER, port=MQTT_PORT)
ser.close()

```
**MQTT_SERVER = PLEASE USE THE IP FOR YOUR MQTT BROKER
**MQTT_PORT = PLEASE USE THE PORT FOR YOUR MQTT BROKER
**ser = ... usb-Prolific_Technology_Inc._USB-Serial_Controller-if00-port0 -> Please modify with your result of ls -l /dev/serial/by-id
