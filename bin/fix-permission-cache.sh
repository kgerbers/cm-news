#!/bin/sh

# Fix var directory rights

set -e

BASEDIR=${1-$(dirname $(dirname $0))}
USER=www-data
GROUP=www-data

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

mkdir -p $BASEDIR/var/cache
chown -hR $USER:$GROUP $BASEDIR/var/cache
chmod -R u=rwX,g=rX,o= $BASEDIR/var/cache
mkdir -p $BASEDIR/var/logs
chown -hR $USER:$GROUP $BASEDIR/var/logs
chmod -R u=rwX,g=rX,o= $BASEDIR/var/logs
mkdir -p $BASEDIR/var/sessions
chown -hR $USER:$GROUP $BASEDIR/var/sessions
chmod -R u=rwX,g=rX,o= $BASEDIR/var/sessions
mkdir -p $BASEDIR/files
chown -hR $USER:$GROUP $BASEDIR/files
chmod -R u=rwX,g=rX,o= $BASEDIR/files

