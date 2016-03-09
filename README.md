Trainmaster
===========
Web UI to control Marklin tracks or any other brand

This application uses python 2 external libs RPi.GPIO and SHIFTPI

https://github.com/mignev/shiftpi

## Steps to deploy

1) Get this project Duh!

$ git clone  https://github.com/chapunazar/trainmaster.git


2) Install RPi.GPIO lib

$ sudo apt-get update && sudo apt-get -y install python-rpi.gpio python-dev

Note: If you encounter problems with the GPIO lib, install then python-dev and then manually install the rpi.gpio lib from here
https://pypi.python.org/pypi/RPi.GPIO/

$ sudo python setup.py install


3) Install ShiftPI lib for managing shift registers

$ git clone git://github.com/mignev/shiftpi.git

$ sudo python shiftpi/setup.py install

$ sudo rm -rf shiftpi


4) Add write permission to relays.ini so web user as www-data may edit the file. example:

$ chmod 666 relays.ini

5) Need to provide more privileges to www-data to access GPIO.

$ sudo visudo

add the line:

www-data ALL=(ALL) NOPASSWD: ALL

This one is too risky! basically www-data has root access! Cross site-scripting could allow someone to become root by tricking your server into running a command possibly destroying your Pi. Instead you should make a group with

 sudo addgroup gpio (if does not exist)
 
then give access to GPIO pins


 sudo chown -R root:gpio /sys/class/gpio
 
then add www-data to gpio group

 sudo adduser www-data gpio
 
Finally, remove www-data from sudoers!
