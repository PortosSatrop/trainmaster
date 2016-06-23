Trainmaster
===========
Web UI to control Marklin tracks or any other brand

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


2) Install Python and RPi.GPIO lib
If Python is not installed then
```
$sudo apt-get install python-dev python3-dev
```
Then RPi.GPIO lib
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
5) Need to provide more privileges to www-data to access GPIO.
```
$ sudo visudo
```
add the line:
`www-data ALL=(ALL) NOPASSWD: ALL`

This one is too risky! basically www-data has root access! Cross site-scripting could allow someone to become root by tricking your server into running a command possibly destroying your Pi. Instead you should make a group with
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
Finally, remove www-data from sudoers!

## Copyright
Copyright (c) 2016 Manuel Nazar Anchorena. See LICENSE for further details.
