# Attention IF you using your local computer you need delete line 6,7,9 and change line 18 https to http  !!!!
# line 13 you have to use USB0 to x depending what usb port you using with your USB adapter. In case of seriell adapter you have to use  /dev/ttyAMA0 not USB0

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
