import time
import binascii
import serial
import os, ssl
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
filename = '/home/pi/pyhton/transfer.txt'
with open(filename, 'rb') as f:
    content = f.read()
if content == "504254303274C0D":
   qcode = "\x50\x42\x54\x30\x32\x74\x0C\x0D"
else:
    qcode = content.decode("hex")
print qcode
ser.write(qcode)
result = ser.read(5)
print result
ser.close()
f.close()
