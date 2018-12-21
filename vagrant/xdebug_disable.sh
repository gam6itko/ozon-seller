#!/bin/sh
set -ex

if [ -f /etc/php.d/xdebug.ini ]; then
    (
        cd /etc/php.d/
        f=$(ls *xdebug.ini) && sudo mv ${f} ${f}.off || echo -n
    )
fi