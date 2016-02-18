# trainmaster
Web UI to control a Marklin train 

This application uses python 2 external libs RPi.GPIO and SHIFTPI

https://github.com/mignev/shiftpi

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
