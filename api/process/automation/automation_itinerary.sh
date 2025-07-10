#!/bin/bash

# Get the current directory of this script
DIR="$(cd "$(dirname "$0")" && pwd)"
SCRIPT="$DIR/automation_itinerary.php"

while true
do
    if ! pgrep -f "$SCRIPT" > /dev/null
    then
        echo "$(date): automation_itinerary.php is not running. Starting..."
        nohup php "$SCRIPT" > /dev/null 2>&1 &
    else
        echo "$(date): automation_itinerary.php is running."
    fi

    sleep 5
done
