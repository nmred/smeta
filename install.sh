#!/bin/bash

case $1 in
build_rpm)
	DIST_MASTER="/usr/local/tmp_swansoft_git"	
	;;
*)
	DIST_MASTER="/usr/local/swan"
esac


CUR_PWD=`pwd`
SF_DIR="$CUR_PWD/src/sf"
DIST_SF="$DIST_MASTER/sf"

CONFIGURE_CMD="/usr/local/dev_swan/configure -d "


# 生成主仓库的 Makefile
CMD="$CONFIGURE_CMD $DIST_MASTER"
echo $CMD
eval $CMD

# 生成 SF1 仓库的 Makefile
cd $SF_DIR
CMD="$CONFIGURE_CMD $DIST_SF"
echo $CMD
eval $CMD

cd $CUR_PWD
make install
