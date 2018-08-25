 #!/usr/bin/env python
import time
import serial
import os, ssl
QPIRI= "\x51\x50\x49\x52\x49\xF8\x54\x0D"
fobj_in = open("qpiri.txt")
i = 0
while i < 1:
    ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=5)
    if serial.serialutil.SerialException and ser.isOpen():
       ser.write(QPIRI)
       result = ser.read(68)
       if '(' in result and len(result)>50:
          fobj_out = open("qpiri.txt","w")
          fobj_out.write(result)
          fobj_out.close()
          print "success"
          i = 1
    ser.close()
fobj_in.close()
