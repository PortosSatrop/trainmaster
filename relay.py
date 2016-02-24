#-------------------------------------------------------------------------------
# Name:			relay
# Purpose		This program interfaces from TrainMaster UI service.php to the Raspberry Pi
# Arguments list:	method = toggle | allstop | allstart
#			relay = relay code. Example P-REL01 OR circuit = Circuit code "A", "B", "C"
#
# Author:		Manuel
# Shift register pinout :
#	SER = 25 (GPIO RPI) #pin 14 on the 75HC595
#	RCLK = 24 (GPIO RPI) #pin 12 on the 75HC595
#	SRCLK = 23 (GPIO RPI) #pin 11 on the 75HC595
# 
# https://github.com/mignev/shiftpi


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
#import shiftpi

relaylib.log("INFO","Starting...")

HIGH = "HIGH"
LOW = "LOW"
ALL = "ALL"

def delay(ms):
	print "Waiting for " + str(ms) + "ms"
	#shiftpi.delay(ms)

def digitalWrite(relay, value):
	print "Puting " + str(relay) + " to " + value
	# Transfor relay id into a register output

"""	if value==HIGH:
		value = shiftpi.HIGH
	if value==LOW:
		value = shiftpi.LOW
	if pin==ALL:
		pin = shiftpi.ALL

	shiftpi.digitalWrite(pin, value)
"""
def shiftRegisters(number):
	print "Using " + str(number) + " register(s)"
	#shiftpi.shiftRegisters(number)

# Helpful methods

## returns Json
def getRelayData(relays,cat,relay):
	data_str =  relays.get(cat, relay)
	data_str = data_str.replace("'","")
	data = json.loads(data_str)
	return data

# Return based on the id the category: power or switch
def findRelayCategory(relay):
	aux = relay.strip().split('-')
	cat = "unknown"
	if aux[0].upper()=="P":
		cat="power"
	if aux[0].upper()=="S":
		cat="switch"
	
	return cat

# Stop all Power Relays
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


# Start all power relays
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


# Set the relay value
def setRelayValue(relays, category, relay, value):
	digitalWrite(relay, value)	
	data = getRelayData(relays,category,relay)
	data['value']=value
	data = "'" + json.dumps(data) + "'"
	relays.set(category, relay, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)

# Start a specific circuit
def startCircuit(relays, circuit):
	relaysCirc = getRelaysInCircuit(relays,circuit)
	for relay in relaysCirc:
		digitalWrite(relay, HIGH)	
		data = getRelayData(relays,"power",relay)
		data['value']=HIGH
		data = "'" + json.dumps(data) + "'"
		relays.set("power", relay, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)

# Stop a specific circuit
def stopCircuit(relays, circuit):
	relaysCirc = getRelaysInCircuit(relays,circuit)
	for relay in relaysCirc:
		digitalWrite(relay, LOW)	
		data = getRelayData(relays,"power",relay)
		data['value']=LOW
		data = "'" + json.dumps(data) + "'"
		relays.set("power", relay, data)
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)



def getRelaysInCircuit(relays,circuit):
	options = relays.options("power")
	relaysCirc = []
	for option in options:
		data = getRelayData(relays,"power",option)
		if data['circuit'] == circuit:
			relaysCirc.append(option)

	return relaysCirc



#Get the method and the relay from command line
if len(sys.argv) < 3:
    relaylib.log("ERROR","Incorrect number of arguments to run the program")
    sys.exit(1)

# Getting the status of all the relays
relays = ConfigParser.RawConfigParser()
relays.read('relays.ini')

method = sys.argv[1]

shiftRegisters(1)

### Define actione based on method received
# toggle the value of a specific relay
if method=="toggle":
	relay = sys.argv[2]
	category = findRelayCategory(relay)
	data = getRelayData(relays, category, relay)
	if data['value'] == LOW:
		setRelayValue(relays, category, relay, HIGH)
	if data['value'] == HIGH:
		setRelayValue(relays, category, relay, LOW)

# Stop all Power Relays
if method=="allstop":
	allStop(relays)

# Start all power relays
if method=="allstart":
	allStart(relays)

# Start a specific Circuit
if method=="startcircuit":
	circuit = sys.argv[2]
	startCircuit(relays, circuit)

# Stop a specific Circuit
if method=="stopcircuit":
	circuit = sys.argv[2]
	stopCircuit(relays, circuit)

