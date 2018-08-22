import time
import binascii
import serial
import os, ssl
filename = 'transfer.txt'
with open(filename, 'rb') as f:
     content = f.read()
if content == "504254303274C0D":
   qcode = "\x50\x42\x54\x30\x32\x74\x0C\x0D"
else:
    qcode = content.decode("hex")
i = 0
while i < 1:
      ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=5)
      if serial.serialutil.SerialException and ser.isOpen():
         ser.write(qcode)
         result = ser.read(5)
         if '(A' in result:
            print "success"
            i = 1
         if '(N' in result:
            print "unsuccessful"
            i = 1
      ser.close()
