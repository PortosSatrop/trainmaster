#import shiftpi
import shiftpi.shiftpi as shiftpi

shiftpi.shiftRegisters(3) 

# turns shift register's pin 0 to HIGH
shiftpi.digitalWrite(5, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 1 to HIGH
shiftpi.digitalWrite(15, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 0 to LOW
shiftpi.digitalWrite(5, shiftpi.LOW)
shiftpi.delay(1000)

# turns shift register's pin 0 to HIGH
shiftpi.digitalWrite(5, shiftpi.HIGH)
shiftpi.delay(1000)

# turns shift register's pin 0 to LOW
shiftpi.digitalWrite(5, shiftpi.LOW)
shiftpi.delay(1000)




# turns shift register's pin 1 to LOW
shiftpi.digitalWrite(15, shiftpi.LOW)
shiftpi.delay(1000)


# turns all shift register pins to HIGH
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.HIGH)
shiftpi.delay(1000)

#and to low
shiftpi.digitalWrite(shiftpi.ALL, shiftpi.LOW)
