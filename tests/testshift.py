#import shiftpi
import shiftpi.shiftpi as shiftpi

shiftpi.shiftRegisters(3) 

#initialize
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)

# turns shift register's pin to HIGH
shiftpi.digitalWrite(0, shiftpi.HIGH)
shiftpi.delay(500)

shiftpi.digitalWrite(7, shiftpi.HIGH)
shiftpi.delay(500)

shiftpi.digitalWrite(20, shiftpi.HIGH)
shiftpi.delay(500)


# turns shift register's pin to LOW
shiftpi.digitalWrite(1, shiftpi.LOW)
shiftpi.delay(500)


# turns all shift register pins to HIGH
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.HIGH)
shiftpi.delay(1000)

#and to low
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
shiftpi.delay(1000)
