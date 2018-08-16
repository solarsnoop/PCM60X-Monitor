# in line 7 you have to use USB0 to x depending what usb port you using with your USB adapter. In case of seriell adapter you have to use  /dev/ttyAMA0 not USB0

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
