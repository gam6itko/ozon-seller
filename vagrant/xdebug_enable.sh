#!/bin/sh
set -ex

(
  cd /etc/php.d/
  f=$(ls *xdebug.ini.off | sed -e "s/\.off//")
  mv ${f}.off ${f}
)