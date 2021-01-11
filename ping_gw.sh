#!/bin/sh

cd $(dirname $0)

if [ ! -f ".default_gw" ]; then
  ./get_default_gw.php || exit 2
fi

DEFAULT_GW=`cat .default_gw`
NOW=`date -u`
LOG_FILE=~/ping_gw.log

ping -q -w 1 -c 1 $DEFAULT_GW > /dev/null

if [ $? -eq 0 ]; then
  echo $NOW: Internet ok
  tail -n 1 $LOG_FILE | grep -q ok || echo $NOW: Internet ok >> $LOG_FILE

elif [ $? -eq 1 ]; then
  echo $NOW: Internet down
  echo $NOW: Internet down >> $LOG_FILE

  echo $NOW: Restart router!
  echo $NOW: Restart router! >> $LOG_FILE
  ./restart_router.php && rm -f .default_gw

  exit 1
fi
