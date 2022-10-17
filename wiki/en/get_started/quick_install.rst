*************
Installation
*************

In order to install MagnusBilling you'll need a server with CentOS 7 or Debian 10, minimal install.

    
**1.** Execute the following commands as root to run the script that will install MagnusBilling, Asterisk and all dependencies needed like: IPTables, Fail2ban, Apache, PHP and MySQL.

Install CentOS 7 **minimal**.

::
     
    cd /usr/src/
    yum -y install wget
    wget https://raw.githubusercontent.com/magnussolution/magnusbilling7/source/script/install.sh
    chmod +x install.sh
    ./install.sh  

**2.** During the install you'll be asked what language MagnusBilling should use. Choose by typing the number of the language.

::

   Install complete. The server will restart automatically..

   Use a browser to access the interface.
      Go to: http://000.000.000.000
      User: root
      Password: magnus (Remember to change the password)


.. image:: ../img/ilogin.png
        :scale: 80%
