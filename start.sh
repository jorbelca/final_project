#!/bin/bash
rm -f /var/run/fail2ban/fail2ban.sock
service fail2ban start
exec apache2-foreground

#limpieza de sockets de fail2ban
#inicia el servicio de fail2ban
#service fail2ban status
