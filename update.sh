#!/bin/bash

git fetch
git pull
git submodule init
git submodule update
cd src/sf
git fetch
git pull origin master
