import time
import serial
import os, ssl
if (not os.environ.get('PYTHONHTTPSVERIFY', '') and
    getattr(ssl, '_create_unverified_context', None)): 
    ssl._create_default_https_context = ssl._create_unverified_context
QPIRI= "\x51\x50\x49\x52\x49\xF8\x54\x0D"
apikey="dd37f0cfa7c8ccef5b4fa5fa81282650"
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
ser.write(QPIRI)
result = ser.read(70)
ser.read()
ser.read()
print result
ser.close()
fobj_in = open("/home/pi/pyhton/qpiri.txt")
fobj_out = open("/home/pi/pyhton/qpiri.txt","w")
i = 1
fobj_out.write(result)
fobj_in.close()
fobj_out.close()
