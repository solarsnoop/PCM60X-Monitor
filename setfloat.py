# line 7 you have to use USB0 to x depending what usb port you using with your USB adapter. In case of seriell adapter you have to use  /dev/ttyAMA0 not USB0

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
