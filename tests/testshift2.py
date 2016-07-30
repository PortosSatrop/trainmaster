#import shiftpi
import shiftpi.shiftpi as shiftpi

shiftpi.shiftRegisters(4) 

shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)

for i in range(0,32):
	print (i+1)
	shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
	shiftpi.digitalWrite(i, shiftpi.HIGH)
	shiftpi.delay(500)


# turns all shift register pins to HIGH
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.HIGH)
shiftpi.delay(1000)

#and to low
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)
