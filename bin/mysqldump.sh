#!/bin/bash

date=$(date +"%d%m%Y")
rm dump_$date.sql
mysqldump -u root -p short_urls > dump_$date.sql
