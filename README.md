Pros vs. Joes: Raiders0fTheL0stArp
===============================

This repository houses all code, material and files used by the the Raiders0fTheL0stArp team for the [Pros vs. Joes] competition held on [Friday, October 26th, 2018](https://bsidesdc2018.busyconf.com/schedule#day_5acff42aec4a15f24e000035) at [Bsides DC].

We've coordinated this repo and continued our communication through the [Slack} channel: [https://raidersofthelostarp.slack.com](https://raidersofthelostarp.slack.com).


The Team
===========

1. __T3cht0n1c__ - Team Captain.
2. __Alex M [alanman]__ - USCYBERCOM, in charge of offensive cyber teams. Firewall guru.
3. __John McGill [blue.level.2]__ 
4. __chris__
5. __Erik Clark [erik]__ - Tech at NASA.
6. __Girish Mukhi [G1d30n]__ - Security analyst.
7. __Carlos Embury [papadynamite]__ - 
8. __Shohn__ - Cisco's cyber ops. Experienced with Linux, Powershell & Python.
9. __Olivia__ - AWS, JavaScript. 
10. __John Hammond__ - Coast Guard security tester. 


The Game
===========

[PvJ] is a classic Red Team (Pros) vs. Blue Team (Joes) exercise. We act as the Blue Team to defend a computer network of different services and technologies.

The game lasts for only one day, as opposed to the usual "2-day runtime." 

Formal rules (at least for the previous game) are explained here: [http://prosversusjoes.net/BSidesLV2017ProsVJoesCTFrules.html](http://prosversusjoes.net/BSidesLV2017ProsVJoesCTFrules.html)

Mechanics
---------

* There will be _full Internet_ access throughout the game. The connection to the Range is done through an OpenVPN certificate, and Ethernet cables are recommended.

* _The Storefront_ offers the ability to buy reverts or updates if we get locked out of a box.

* Red Team has _prior access_ to the environment. They will place "three levels" of malware drops, (1) easy-to-spot files or outbound connections, (2) more well-hidden code or scripts like in PHP files, and (3) low-level rootkits.

* _"Puzzle boxes"_ will be deployed throughout the game, which offer another challenge of attack and defense.

* The simulated user space "gray team" will likely _not be part of this game_ due to the shortened timespan.

* The scoring engine works strictly off of DNS. DNS and the PfSense firewall should be some of our top priorities. Scoring engine is seemingly open-source: [https://github.com/dichotomy/scorebot](https://github.com/dichotomy/scorebot)

Reading Resources
------------

* [Game Analysis](https://blog.infosecanalytics.com/2018/08/game-analysis-of-2018-pros-vs-joes-ctf.html)
* ANY of the linked articles or material on [http://prosversusjoes.net/](http://prosversusjoes.net/)


Assumptions
---------------

For Scoring, it seems for services that need authentication there should be a user `blueteam` with password `scorebot`?? May be necessary for FTP.


Network blocks: _(this is according to the Red Cell, and may be our own blocks...)_


> 10.100.101.0/24, 10.100.103.0/24, 10.100.104.0/24,10.100.105.0/24, 172.16.101.0/24, 172.16.103.0/24, 172.16.104.0/24, 172.16.105.0/24  


We can assume we will be facing the following services:

```
ftppub
www
zcms
joomla
biz
mail
```

> A Windows Server 2008 acting as a Domain Controller
>
> Several Linux servers in multiple flavors: Ubuntu, CentOS, SuSE
>
> A number of Windows XP machines

Priorities and Tools
=======================

A general playbook that may help is the Blue Team Field Manual. A digital copy can be found here: [https://github.com/JohnHammond/blueteamfieldmanual](https://github.com/JohnHammond/blueteamfieldmanual).

General
---------

* __DO__ network scan to find boxes that the customer did not tell you about. 
* ___DO NOT___ block ICMP, as that is necessary for scoring!!
* Change Passwords (duh)! There should be no default passwords on the game board.
* Check Network Shares. Is there anything left available (flags) that there should not be? Can we disallow the ability for network shares to be enumerated?
* Check the temp folder. `/tmp` on Linux or `%TEMPDIR%` on Windows.


* Some potential places for flags the Red Team may be hunting for in-game:

> What is the flag in the Shared folder of 2k3?
> What is the full name for the domain user "flag1"?
> What is the full name for the domain user "flag2"?
> What is the full name for the domain user "flag3"?
> What is the full name for the domain user "flag4"?
> What is the full name for the domain user "flag5"?
> What is the body of the flag email stored on the mail server?
> What is the value of the flag in the test database on the database server used by ZeroCMS?
> What is the flag in the root of Win2k3?
> What is the flag in the root of Win2k8?
> What is the user id for the flag user in the zerocms database on the database server used by ZeroCMS?
> What is the flag in the user table of the MySQL database on 2k8?
> What is the admin user's e-mail address for Zen Cart?
> What is the value of the flag in the database on the Wiki server?


* Some scripts and resources are available here: [https://github.com/4ndronicus/pros-vs-joes](https://github.com/4ndronicus/pros-vs-joes).

Domain Controller
---------------------

We do want to update PowerShell to version 5.1. 

We will need the Windows Management Framework 5.1 to be able to do this. [https://www.microsoft.com/en-us/download/details.aspx?id=54616](https://www.microsoft.com/en-us/download/details.aspx?id=54616).

To do that, we first need Microsoft .NET Framework 4.5.2. [https://www.microsoft.com/en-ca/download/confirmation.aspx?id=42642](https://www.microsoft.com/en-ca/download/confirmation.aspx?id=42642)


DNS
----------

* There is a common BIND 9 bug that is being used to consistently take down DNS and scoring.


Web Services
--------------

* __Local File Inclusion__: Test for local file inclusion on web servers. This looks like a common issue on the port `8800` service. May be a culprit if we are given the "Web Dev" box.

![http://4.bp.blogspot.com/-AgPQXKt-7Ik/VcTocWXBirI/AAAAAAAAAxo/gC6FLsbRXQ4/s1600/lfi.png](http://4.bp.blogspot.com/-AgPQXKt-7Ik/VcTocWXBirI/AAAAAAAAAxo/gC6FLsbRXQ4/s1600/lfi.png)

* __SQL Injection__: Check for SQL injection in your web applications. A culprit seen before is ZeroCMS. `sqlmap` will allow the Red Team full control...

![http://4.bp.blogspot.com/-ySMWtsN5Fhg/VcVyfBTkrbI/AAAAAAAAAyI/ILU7o-PSQoQ/s1600/Screenshot%2Bfrom%2B2015-07-20%2B00%253A43%253A21.png](http://4.bp.blogspot.com/-ySMWtsN5Fhg/VcVyfBTkrbI/AAAAAAAAAyI/ILU7o-PSQoQ/s1600/Screenshot%2Bfrom%2B2015-07-20%2B00%253A43%253A21.png)

* __phpMyAdmin__: Check if phpMyAdmin is left accessible or if default credentials are in use.

* __Command injection__: Check for simple command injection. Looks like this is common on `Contact Us` pages within some applications (they say the Subject field is what was vulnerable).

* __Log Poisoning__: can you lock site access to certain User Agents?



Pro Tips
===========

Notes shamelessly stolen from [https://systemoverlord.com/2015/08/15/blue-team-players-guide-for-pros-vs-joes-ctf/N](https://systemoverlord.com/2015/08/15/blue-team-players-guide-for-pros-vs-joes-ctf/)

> Changing all the passwords to one “standard” may be attractive, but it’ll only take one keylogger from red cell to make you regret that decision.
> Consider disabling password-based auth on the linux machines entirely, and use SSH keys instead.
> The scoring bot uses usernames and passwords to log in to some services. Changing those passwords may have an adverse effect on your scoring. Find other ways to lock down those accounts.
> Rotate roles, giving everyone a chance to go on both offense and defense.


[Bsides DC]: http://bsidesdc.org/
[Pros vs. Joes]: http://prosversusjoes.net/
[PvJ]: http://prosversusjoes.net/
[Slack]: https://slack.com/