#!/bin/sh
set -e

if [ "$#" -gt 0 ]; then
  exec "$@"
fi
