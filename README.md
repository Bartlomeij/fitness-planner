Fitness Planner
============

You can use this Docker on Ubuntu 16.04+ and Mac Os Mojave 10.14+

## Install Docker

1. Install Docker Community Edition - https://docs.docker.com/install/linux/docker-ce/ubuntu/#install-from-a-package
2. Install Docker Compose - https://docs.docker.com/compose/install/

## Install & Configure GIT

```
sudo apt install git && \
git config --global user.email "email@example.com" && \
git config --global user.name "username"
```

## Clone the repository

```
git clone git@github.com:Bartlomeij/fitness-planner.git ~/Code/fitness
``` 

## Add domain to hosts

Add line below to `/etc/hosts` file:
```
127.0.0.1 fitness.local
```

## Initialize project
```
cd ~/Code/fitness
sh ./build.sh
```
