 #!/usr/bin/env python
import time
import serial
import os, ssl
QPIGS = "\x51\x50\x49\x47\x53\xB7\xA9\x0D"
fobj_in = open("qpigs.txt")
i = 0
while i < 1:
    ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=5)
    if serial.serialutil.SerialException and ser.isOpen():
       ser.write(QPIGS)
       result = ser.read(68)
       if '(' in result:
          fobj_out = open("qpigs.txt","w")
          fobj_out.write(result)
          fobj_out.close()
          print "success"
          i = 1
    ser.close()
fobj_in.close()
