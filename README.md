Trainmaster
===========
Web UI to control Marklin tracks or any other brand

Though its aimed to power tracks or turnouts actually the architecture is valid for any use. Of course the graphics would need to be adapted

Features:
* Relay control on power line into tracks. Circuit must be segmented (isolated) in order for controlling parts of it. GND must not be segmented only 3-track (power track).
* Relay control on lights or any other ON/OFF device.
* Relay control on turnouts (experimental for the moment). TODO 3-turnout challenge

This application uses python 2 external libs RPi.GPIO and SHIFTPI

`https://github.com/mignev/shiftpi`

Thanks Marian Ignev for this great library!

## Architecture
The goal is to control the relays from a nice and easy to use web interface. Using mainly JavaScript and Jquery, upon a click on a track control an http request is sent to itself to consume a webservice `service.php`. This file creates the string to execute a python program `relay.py` from PHP that, using the `shiftpi` library communicates with the GPIO.

JSCRIPT <-> PHP <-> PYTHON <-> Shiftpi <-> GPIO <-> Relays

## Steps to deploy

1) Get this project Duh!
```
$ git clone  https://github.com/chapunazar/trainmaster.git
```


2) Install Python-dev and RPi.GPIO lib
There are several ways to install python, most probably python 2 is there already
```
$ sudo apt-get update && sudo apt-get -y install python-rpi.gpio python-dev
```
Note: Only if you encounter problems with the GPIO lib, install then python-dev and then manually install the rpi.gpio lib from here
https://pypi.python.org/pypi/RPi.GPIO/. Check documentation for installation here as well https://sourceforge.net/p/raspberry-gpio-python/wiki/install/
```
$ sudo python setup.py install
```

3) Install ShiftPI lib for managing shift registers
```
$ git clone git://github.com/mignev/shiftpi.git
$ sudo python shiftpi/setup.py install
$ sudo rm -rf shiftpi
```

4) Inside trainmaster directory: Add write permission to relays.ini so web user as www-data may edit the file. example:
```
$ chmod 666 relays.ini
```
5) Need to provide privileges to www-data to access GPIO.
```
$ sudo addgroup gpio (if does not exist)
``` 
then give access to GPIO pins
```
$ sudo chown -R root:gpio /sys/class/gpio
``` 
then add www-data to gpio group
```
$ sudo adduser www-data gpio
```

If the above doesnt work and your Pi will not be connected to the INET then try the following which is too risky! basically www-data will have root access! Cross site-scripting could allow someone to become root by tricking your server into running a command possibly destroying your Pi.
```
$ sudo visudo
```
add the line:
`www-data ALL=(ALL) NOPASSWD: ALL`

6) Working in a non RaspPi.

If you are not coding in the RPi then you will receive error from the RPi GPio stating the device is not a RPi. For that purpose edit the file .env and put "dev" in the APP_ENV key. Once in the RPi simply put "production". This file is version control ignored 
```
[MAIN]
APP_ENV=dev
```


Happy relaying!

## Copyright
Copyright (c) 2016 Manuel Nazar Anchorena. See LICENSE for further details.
