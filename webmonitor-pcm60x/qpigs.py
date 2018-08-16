import time
import serial
import os, ssl
QPIGS = "\x51\x50\x49\x47\x53\xB7\xA9\x0D"
ser = serial.Serial(port='/dev/ttyUSB0',baudrate=2400,timeout=2)
ser.write(QPIGS)
result = ser.read(70)
ser.read()
ser.read()
ser.close()
fobj_in = open("qpigs.txt")
fobj_out = open("qpigs.txt","w")
i = 1
fobj_out.write(result)
fobj_in.close()
fobj_out.close()
