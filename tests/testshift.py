#import shiftpi
import shiftpi.shiftpi as shiftpi

shiftpi.shiftRegisters(8) 

#initialize
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)

# turns shift register's pin to HIGH
shiftpi.digitalWrite(44, shiftpi.HIGH)
shiftpi.delay(500)

shiftpi.digitalWrite(45, shiftpi.HIGH)
shiftpi.delay(500)

shiftpi.digitalWrite(46, shiftpi.HIGH)
shiftpi.delay(500)


# turns shift register's pin to LOW
shiftpi.digitalWrite(44, shiftpi.LOW)
shiftpi.delay(500)


# turns all shift register pins to HIGH
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.HIGH)
shiftpi.delay(1000)

#and to low
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)
