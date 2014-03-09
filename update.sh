#!/bin/bash

git fetch
git pull

# 更新子模块
git submodule init
git submodule update
cd src/sf
git fetch
git pull origin master
