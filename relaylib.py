#-------------------------------------------------------------------------------
# Name:			relaylib
# Purpose:		Support functions for relay.py
#
# Author:		Manuel
#-------------------------------------------------------------------------------

from datetime import datetime, date, time

def log(type, txt):
    t=datetime.now()
    d=t.strftime("%d/%b/%Y")
    s=t.strftime("%H:%M:%S")
    linea = '['+d+':'+s+'] - ' + type + ' - \"' + txt +'\"'
    print (linea)

def vardump(var):
    print ("Type: " + str(type(var)))
    print (var)
