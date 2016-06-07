#!/bin/bash

cd /var/www/curupira/script/busca_info_rede

./usrmgr-ng $1 $2 $3 2> /tmp/teste.txt
#echo "A-> $1 - $2 - $3 "> /tmp/teste.txt
