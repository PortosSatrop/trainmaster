
"""
This program interfaces from TrainMaster UI service.php to the Raspberry Pi

Arguments list:
 method = toggle | allstop | allstart
 relay = relay code

Author: Manuel


# Note this is simple GPIO example
Source http://openmicros.org/index.php/articles/94-ciseco-product-documentation/raspberry-pi/217-getting-started-with-raspberry-pi-gpio-and-python

import RPi.GPIO as GPIO
GPIO.setup(18, GPIO.OUT)
GPIO.output(18, False)

Shift register:
SER = 25 (GPIO RPI) #pin 14 on the 75HC595
RCLK = 24 (GPIO RPI) #pin 12 on the 75HC595
SRCLK = 23 (GPIO RPI) #pin 11 on the 75HC595

"""


import sys
#from shiftpi import HIGH, LOW, digitalWrite, delay


#### Dummy functions to replace Shifypi's until its installed in the Raspberry Pi
HIGH = "high"
LOW = "low"
ALL = "all"

def delay(ms):
	print "Waiting for " + str(ms) + "ms"

def digitalWrite(pin, value):
	print "Puting " + str(pin) + " to " + value

def shiftRegisters(number):
	print "Using " + str(number) + " register(s)"

#### comment until here

# Helpful methods
def allStop():
	digitalWrite(ALL, LOW)

def allStart():
	digitalWrite(ALL, HIGH)


def relayHigh(relay):
	digitalWrite(relay, HIGH)
	
def relayHigh(relay):
	digitalWrite(relay, HIGH)

#Function to know the current status
def getCurrentStatus(relay):
	return LOW

#Get the method and the relay from command line
len(sys.argv)

if len(sys.argv) < 3:
    sys.stderr.write('Usage: sys.argv[0] method relay')
    sys.exit(1)

method = sys.argv[1]
relay = sys.argv[2]

shiftRegisters(1)

if method=="toggle":
	status = getCurrentStatus(relay)
	if status == LOW:
		relayHigh(relay)
	if status == HIGH:
		relayLow(relay)


if method=="allstop":
	allStop()

if method=="allstart":
	allStart()
