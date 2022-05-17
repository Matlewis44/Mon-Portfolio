#!/usr/bin/env python
# -*- coding: utf-8 -*-

#sudo chmod a+rw /dev/ttyUSB0

import sys #pour utiliser des paramètres au script
#import socket
import serial
import time
#import subprocess
import datetime

#dev = subprocess.check_output('ls /dev/ttyUSB*', shell=True)

#try:
#	ser = serial.Serial(dev.strip(), 9600)
#except:
#	print "Arduino not connected"

ser = serial.Serial('/dev/ttyUSB0', 9600)

if len(sys.argv) == 1:
	print( "\tUsage: python send.py stringValue")
elif len(sys.argv) == 2:
	toArduino = sys.argv[1]
	toArduinoEncode = toArduino.encode()
	starttime = datetime.datetime.now()
	ok = False
	while ok == False and datetime.datetime.now()-starttime < datetime.timedelta(seconds=10):	
		if(ser.in_waiting>0):
			#sending string to Arduino
			ser.write(toArduinoEncode)
			time.sleep(.1)
			ok = True
			#for i in range(2):
				#this prints the string from the Arduino
			line = ser.readline()
			i = 0
			while line[i]==' ':
				i = i + 1
				line = line[i:]
			print(line)
			ser.close()

