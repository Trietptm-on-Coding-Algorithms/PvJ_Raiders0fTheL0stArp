#!/bin/bash

#-----------------------------------------------------

#### This is just ripped from http://www.unixwiz.net/techtips/bind9-chroot.html
#### It looks to just create a user and prepare a home directory "jail"

# create initial named user and group

groupadd named
useradd -g named -d /chroot/named -s /bin/true named
passwd -l named  #  "lock" the account

# Remove all the login-related trash under the newly-created home directory

rm -rf /chroot/named

# Re-create the top level jail directory

mkdir -p /chroot/named
cd /chroot/named

# create the hierarchy

mkdir dev
mkdir etc
mkdir logs
mkdir -p var/run
mkdir -p conf/secondaries

# create the devices, but confirm the major/minor device 
# numbers with   "ls -lL /dev/zero /dev/null /dev/random"

mknod dev/null c 1 3
mknod dev/zero c 1 5
mknod dev/random c 1 8

# copy the timezone file

cp /etc/localtime etc

# Create the configuration file.
# This is actively creating a symbolic link to a file that 
# does not exists yet

ln -s /chroot/named/etc/bind/named.conf /etc/named.conf