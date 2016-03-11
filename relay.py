#-------------------------------------------------------------------------------
# Name:			relay
# Purpose		This program interfaces from TrainMaster UI service.php to the Raspberry Pi
# Arguments list:	method = toggle | allstop | allstart | startcircuit | stopcircuit | getdevicestatus | allstraight]
#			<arg 2> = [P-REL01 for example] or [category = power for example] or [circuit = A for exmaple] 
#
# Author:		Manuel
# Shift register pinout :
#	SER = 25 (GPIO RPI) #pin 14 on the 75HC595
#	RCLK = 24 (GPIO RPI) #pin 12 on the 75HC595
#	SRCLK = 23 (GPIO RPI) #pin 11 on the 75HC595
# 
# https://github.com/mignev/shiftpi


import relaylib
import sys
import ConfigParser
import json
import shiftpi.shiftpi as shiftpi

#relaylib.log("INFO","Starting...")

HIGH = "HIGH"
LOW = "LOW"
STRAIGHT = "STRAIGHT"
DEVIATE = "DEVIATE"
DEVIATE1 = "DEVIATE1" #This is used for a 3 or 4 way turnout

def delay(ms):
	print "Waiting for " + str(ms) + "ms"
	shiftpi.delay(ms)

#Transforms the current list of power relays into a dictionary used by dgitialwrite. Mainly used for Power relays.
def buildDictionaryOfStates(relays,category):
	dict_states = {}	
	vRelays = relays.options(category)
	for relay in vRelays:
		data = getRelayData(relays, category, relay)
		pin = int(data['registerPin'])
		val = getShiftPiValue(data['value'])
		dict_states[pin] = val
	return dict_states
	
def getShiftPiValue(value):
	if value==HIGH:
		value = shiftpi.HIGH
	if value==LOW:
		value = shiftpi.LOW
	return value

# Never used
def digitalWrite(relays, category, relay, value):
	# Transform relay id into a register output
	data = getRelayData(relays,category,relay)
	pin = data['registerPin']
	print "Puting " + relay + " (pin " + pin + ") to " + value
	pin = int(pin)
	value = getShiftPiValue(value)
	#shiftpi.digitalWrite(pin, value)

def registerWrite(relays, category):
	#I get the current states into a dictionary
	vPins = buildDictionaryOfStates(relays,category)
	# Set all pins at once with the dictionary: shiftpi.startupMode({1: HIGH, 4: HIGH, 6: HIGH}, True) true: executes (latches)
	shiftpi.startupMode(vPins, True)


def shiftRegisters(number):
	#print "Using " + str(number) + " register(s)"
	shiftpi.shiftRegisters(number)
	return 1

# Helpful methods

## returns Json
def getRelayData(relays,cat,relay):
	data_str =  relays.get(cat, relay)
	data_str = data_str.replace("'","")
	data = json.loads(data_str)
	return data

# Return based on the id the category: power or turnout
def findRelayCategory(relay):
	aux = relay.strip().split('-')
	cat = "unknown"
	if aux[0].upper()=="P":
		cat="power"
	if aux[0].upper()=="T":
		cat="turnout"
	if aux[0].upper()=="D":
		cat="direction"

	return cat

# Set the relay value
def setRelayValue(relays, category, relay, value):
	data = getRelayData(relays,category,relay)
	pin = data['registerPin']
	print "Puting " + relay + " (pin " + pin + ") to " + value
	data['value']=value
	data = "'" + json.dumps(data) + "'"
	relays.set(category, relay, data)
	return relays

# Returns the status of all the relays of the corresponding category
def getDeviceStatus(relays,category):
	vRelays = relays.options(category)
	sOut = '{"devices":['
	for relay in vRelays:
		data = getRelayData(relays,category,relay)
		if category == "power":
			sOut = sOut + '{"id":"' + relay + '", "value":"' + data['value'] + '"},'
		if category == "turnout":
			sOut = sOut + '{"id":"' + relay + '", "status":"' + data['status'] + '", "value":"' + data['value'] + '"},'
	# I need to remove the last ,
	sOut = sOut[:-1]
	sOut = sOut + ']}'
	return sOut

# Stores in the device file the status
def saveDeviceStatus(relays):
	with open('relays.ini', 'wb') as relayfile:
	    relays.write(relayfile)

########################## TURNOUT RELAY SECTION ##################

# In order to activete a turnout the relays must act as a pulse (see value in confi.ini file)
# To activate a turnout we will use 2 relays that define whether the action will be to go straight or to use a deviation. TODO with a 3 turnout track
# then we assign each turn out a relay in the return cable "brown"

# Example to activate T-REL01 to deviate
# Set T-REL01 to HIGH

# Set T-DEV to HIGH
# Wait for as long as duration of pulse was set
# Set T-DEV to LOW
# Set T-REL01 to LOW


def setTurnoutValue(relays, category, relay, value, config):
	
	cat_dir = "direction"
	d_str = "d-str"
	d_dev = "d-dev"
	d_dev1 = "d-dev1"

	# Get the pulse duration in ms
	pulse = config.get("config","pulse_duration")
	
	# Define the correct directional relay to use based on value
	if value == STRAIGHT:
		rel_dir = d_str
	if value == DEVIATE:
		rel_dir = d_dev
	if value == DEVIATE1:
		rel_dir = d_dev1

	# Set T-REL01 to HIGH
	setRelayValue(relays, category, relay, HIGH)
	registerWrite(relays, category)
	
	# Set directional relay to HIGH
	setRelayValue(relays, cat_dir, rel_dir, HIGH)
	registerWrite(relays, category)
	
	# Wait for as long as duration of pulse was set
	delay(pulse)

	# Set directional relay to LOW
	setRelayValue(relays, cat_dir, rel_dir, LOW)
	registerWrite(relays, category)

	# Set T-REL01 to LOW
	setRelayValue(relays, category, relay, LOW)
	registerWrite(relays, category)

	# Save the turnout
	data = getRelayData(relays,category,relay)
	data['status']=value
	data = "'" + json.dumps(data) + "'"
	relays.set(category, relay, data)
	return relays

# Set all turnouts straight
def setAllStraight(relays,value):
	category = "turnout"
	vRelays = relays.options(category)
	for relay in vRelays:
		relays = setTurnoutValue(relays, category, relay, STRAIGHT, config)
	return relays


########################## END TURNOUT RELAY SECTION ##################

########################## POWER RELAY SECTION ##################
# Start or Stop all Power Relays
def setAllPowerRelays(relays,value):
	category = "power"
	
	# step 1: Update the device vector
	vRelays = relays.options(category)
	for relay in vRelays:
		data = getRelayData(relays, category, relay)
		data['value']=value
		data = "'" + json.dumps(data) + "'"
		relays.set(category, relay, data)
	
	# step 2: Write to the register
	registerWrite(relays, category)

	return relays	

# Start a specific circuit
def startCircuit(relays, circuit):
	category = "power"
	
	# step 1: Update the device vector
	relaysCirc = getRelaysInCircuit(relays,circuit)
	for relay in relaysCirc:
		data = getRelayData(relays,category,relay)
		data['value']=HIGH
		data = "'" + json.dumps(data) + "'"
		relays.set(category, relay, data)
		
	# step 2: Write to the register
	registerWrite(relays, category)
	
	return relays

# Stop a specific circuit
def stopCircuit(relays, circuit):
	category = "power"
	
	# step 1: Update the device vector
	relaysCirc = getRelaysInCircuit(relays,circuit)
	for relay in relaysCirc:
		data = getRelayData(relays,category,relay)
		data['value']=LOW
		data = "'" + json.dumps(data) + "'"
		relays.set(category, relay, data)
	
	# step 2: Write to the register
	registerWrite(relays, category)
	
	return relays


# Returns an array of relays belonging to a circuit
def getRelaysInCircuit(relays,circuit):
	category = "power"
	options = relays.options(category)
	relaysCirc = []
	for option in options:
		data = getRelayData(relays,category,option)
		if data['circuit'] == circuit:
			relaysCirc.append(option)

	return relaysCirc

########################## END POWER RELAY SECTION ##################


##########################          MAIN           ##################
#Get the method and the relay from command line
if len(sys.argv) < 3:
    relaylib.log("ERROR","Incorrect number of arguments to run the program")
    sys.exit(1)

# Read config file
config = ConfigParser.RawConfigParser()
config.read('config.ini')

# Getting the status of all the relays
relays = ConfigParser.RawConfigParser()
relays.read(config.get("config","device_file"))

method = sys.argv[1]

shiftRegisters(1)

### Define actione based on method received
# toggle the value of a specific relay
if method=="toggle":
	relay = sys.argv[2]
	category = findRelayCategory(relay)
	data = getRelayData(relays, category, relay)
	if category == "power":
		if data['value'] == LOW:
			relays = setRelayValue(relays, category, relay, HIGH)
		if data['value'] == HIGH:
			relays = setRelayValue(relays, category, relay, LOW)
	# Write to the registers
	registerWrite(relays, category)
	if category == "turnout":
		if data['status'] == STRAIGHT:
			relays = setTurnoutValue(relays, category, relay, DEVIATE,  config)
		if data['status'] == DEVIATE:
			relays = setTurnoutValue(relays, category, relay, STRAIGHT, config)

# TODO Special case for 3 or 4 way turnout


# Stop all Power Relays
if method=="allstop":
	relays = setAllPowerRelays(relays,LOW)

# Start all power relays
if method=="allstart":
	relays = setAllPowerRelays(relays,HIGH)

# Start a specific Circuit
if method=="startcircuit":
	circuit = sys.argv[2]
	relays = startCircuit(relays, circuit)

# Stop a specific Circuit
if method=="stopcircuit":
	circuit = sys.argv[2]
	relays = stopCircuit(relays, circuit)

# Return relay status of the specified category
if method=="getdevicestatus":
	category = sys.argv[2]
	out = getDeviceStatus(relays, category)
	print out

# Set all turnouts straight
if method=="allstraight":
	relays = setAllStraight(relays,config)


# Finally save the status of the devices
saveDeviceStatus(relays)
