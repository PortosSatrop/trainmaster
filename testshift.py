#import shiftpi
import shiftpi.shiftpi as shiftpi

shiftpi.shiftRegisters(2) 

# turns shift register's pin 0 to HIGH
shiftpi.digitalWrite(0, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 1 to HIGH
shiftpi.digitalWrite(12, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 0 to LOW
shiftpi.digitalWrite(0, shiftpi.LOW)
shiftpi.delay(1000)

# turns shift register's pin 0 to HIGH
shiftpi.digitalWrite(0, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 0 to LOW
shiftpi.digitalWrite(0, shiftpi.LOW)
shiftpi.delay(1000)




# turns shift register's pin 1 to LOW
shiftpi.digitalWrite(12, shiftpi.LOW)
shiftpi.delay(1000)


# turns all shift register pins to HIGH
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.HIGH)
shiftpi.delay(1000)

#and to low
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
