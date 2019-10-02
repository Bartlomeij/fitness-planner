#!/bin/sh

docker-compose build
docker-compose up -d

echo "Project initialization: 1 minute remaining"
sleep 30
echo "Project initialization: 30 seconds remaining"
sleep 30
