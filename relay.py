#-------------------------------------------------------------------------------
# Name:			relay
# Purpose		This program interfaces from TrainMaster UI service.php to the Raspberry Pi
# Arguments list:	method = toggle | allstop | allstart
# 			category = power | switch
#			relay = relay code
#
# Author:		Manuel
# Shift register pinout :
#	SER = 25 (GPIO RPI) #pin 14 on the 75HC595
#	RCLK = 24 (GPIO RPI) #pin 12 on the 75HC595
#	SRCLK = 23 (GPIO RPI) #pin 11 on the 75HC595



""" Note this is simple GPIO example wihtout shift register
Source http://openmicros.org/index.php/articles/94-ciseco-product-documentation/raspberry-pi/217-getting-started-with-raspberry-pi-gpio-and-python

import RPi.GPIO as GPIO
GPIO.setup(18, GPIO.OUT)
GPIO.output(18, False)

"""

import relaylib
import sys
import ConfigParser
#from shiftpi import HIGH, LOW, digitalWrite, delay

relaylib.log("INFO","Starting...")

#### Dummy functions to replace Shifypi's until its installed in the Raspberry Pi, comment from here...
HIGH = "HIGH"
LOW = "LOW"
ALL = "ALL"

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


def relayHigh(relays, category, relay):
	digitalWrite(relay, HIGH)
	writeRelayStatus(relays, category, relay, HIGH)
	
def relayLow(relays, category, relay):
	digitalWrite(relay, LOW)
	writeRelayStatus(relays, category, relay, LOW)

#Function to know the current status
def getCurrentStatus(relays, category, relay):
	return relays.get(category, relay)

# Write the status permanently
def writeRelayStatus(relays, category, relay, value):
	relays.set(category, relay, value)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)

#Get the method and the relay from command line
if len(sys.argv) < 4:
    relaylib.log("ERROR","Incorrect number of arguments to run the program")
    sys.exit(1)

# Getting the status of all the relays
relays = ConfigParser.RawConfigParser()
relays.read('relays.ini')

method = sys.argv[1]
category = sys.argv[2]
relay = sys.argv[3]

shiftRegisters(1)

# Define actione based on method received
if method=="toggle":
	status = getCurrentStatus(relays, category, relay)
	if status == LOW:
		relayHigh(relays, category, relay)
	if status == HIGH:
		relayLow(relays, category, relay)

if method=="allstop":
	allStop()

if method=="allstart":
	allStart()


