#-------------------------------------------------------------------------------
# Name:			relay
# Purpose		This program interfaces from TrainMaster UI service.php to the Raspberry Pi
# Arguments list:	method = toggle | allstop | allstart
#			relay = relay code. Example P-REL01
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
import json
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

## returns Json
def getRelayData(relays,cat,relay):
	data_str =  relays.get(cat, relay)
	data_str = data_str.replace("'","")
	data = json.loads(data_str)
	return data

def findRelayCategory(relay):
	aux = relay.strip().split('-')
	cat = "unknown"
	if aux[0].upper()=="P":
		cat="power"
	if aux[0].upper()=="S":
		cat="switch"
	
	return cat

def allStop(relays):
	digitalWrite(ALL, LOW)
	options = relays.options("power")
	for option in options:
		data = getRelayData(relays,"power",option)
		data['value']=LOW
		data = "'" + json.dumps(data) + "'"
		relays.set("power", option, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)


def allStart(relays):
	digitalWrite(ALL, HIGH)
	options = relays.options("power")
	for option in options:
		data = getRelayData(relays,"power",option)
		data['value']=HIGH
		data = "'" + json.dumps(data) + "'"
		relays.set("power", option, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)


# Write the status permanently
def writeRelayStatus(relays, category, relay, value):
	data = getRelayData(relays,category,relay)
	data['value']=value
	data = "'" + json.dumps(data) + "'"
	relays.set(category, relay, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)


def relayHigh(relays, category, relay):
	digitalWrite(relay, HIGH)
	writeRelayStatus(relays, category, relay, HIGH)
	
def relayLow(relays, category, relay):
	digitalWrite(relay, LOW)
	writeRelayStatus(relays, category, relay, LOW)


#Get the method and the relay from command line
if len(sys.argv) < 3:
    relaylib.log("ERROR","Incorrect number of arguments to run the program")
    sys.exit(1)

# Getting the status of all the relays
relays = ConfigParser.RawConfigParser()
relays.read('relays.ini')

method = sys.argv[1]
relay = sys.argv[2]
category = findRelayCategory(relay)
shiftRegisters(1)

# Define actione based on method received
if method=="toggle":
	data = getRelayData(relays, category, relay)
	if data['value'] == LOW:
		relayHigh(relays, category, relay)
	if data['value'] == HIGH:
		relayLow(relays, category, relay)

if method=="allstop":
	allStop(relays)

if method=="allstart":
	allStart(relays)


