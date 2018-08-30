 #!/usr/bin/env python
import time
import serial
import os, ssl
import urllib
import binascii
QPIRI= "\x51\x50\x49\x52\x49\xF8\x54\x0D"
fobj_in = open("/var/www/html/monitor/qpiri.txt")
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
if serial.serialutil.SerialException and ser.isOpen():
   ser.write(QPIRI)
   result = ser.read(68)
   if '(' in result and len(result)>50:
      fobj_out = open("/var/www/html/monitor/qpiri.txt","w")
      fobj_out.write(result)
      fobj_out.close()
      fobj_in.close()
QPIGS = "\x51\x50\x49\x47\x53\xB7\xA9\x0D"
fobj_in = open("/var/www/html/monitor/qpigs.txt")
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
if serial.serialutil.SerialException and ser.isOpen():
   ser.write(QPIGS)
   result = ser.read(68)
   if '(' in result and len(result)>66:
      fobj_out = open("/var/www/html/monitor/qpigs.txt","w")
      fobj_out.write(result)
      fobj_out.close()
ser.close()


