#!/bin/bash

LIB_DIR = libwebsockets 

-include ../Config

ALL: compile
	@echo "make ALL"
	#for dir in $(LIB_DIR); do \
	#	make -C $$dir; \
	#done

install:
ifeq ($(CYGWIN_BUILD),1)
	for dir in $(LIB_DIR); do \
		make -C $$dir install FRIEND_PATH=$(FRIEND_PATH); \
	done
	
else
	#sudo cp libwebsockets/build/lib/libwebsockets.so* /usr/lib/
	#cd libwebsockets/build/ ;make install ;cd ../../
endif

setup:
	mkdir -p libwebsockets/build
	mkdir -p libssh2/build
	#cd openssl ; ./config no-shared ; make ; cd ..
	cd openssl ; ./config shared ; make ; cd ..
ifeq ($(OPENSSL_INTERNAL),1)
	echo "internal\n\n\n"
	cd libwebsockets/build/ ; cmake ../ -DCMAKE_C_FLAGS=-fPIC -DLWS_HAVE_SYS_CAPABILITY_H=OFF -DLWS_IPV6=ON -DCMAKE_BUILD_TYPE=DEBUG -DLWS_WITH_LIBUV=ON -DOPENSSL_ROOT_DIR=../../openssl/ -DLWS_OPENSSL_INCLUDE_DIRS=../../openssl/include/ -DLWS_WITH_HTTP2=1 -DOPENSSL_CRYPTO_LIBRARY:FILEPATH=../../openssl/libcrypto.a -DOPENSSL_SSL_LIBRARY:FILEPATH=../..openssl/libssl.a -DOPENSSL_INCLUDE_DIR:FILEPATH=../openssl/include/ -DLWS_OPENSSL_LIBRARIES="../../openssl/libssl.a;../../openssl/libcrypto.a" ; make DEBUG=0 ; cd ../../
else
	echo "shared\n\n\n"
	cd libwebsockets/build/ ; cmake ../ -DCMAKE_C_FLAGS=-fPIC -DLWS_HAVE_SYS_CAPABILITY_H=OFF -DLWS_IPV6=ON -DCMAKE_BUILD_TYPE=DEBUG -DLWS_WITH_LIBUV=ON -DLWS_WITH_HTTP2=1; make DEBUG=0 ; cd ../../
endif
	cp -r libwebsockets/include/* libwebsockets/build/include/
	cd libssh2/build/ ; cmake ../ -DCMAKE_C_FLAGS=-fPIC -DCRYPTO_BACKEND:STRING=Libgcrypt ; make DEBUG=0 ; cd ../../

clean:
	mkdir -p libwebsockets/build
	mkdir -p libssh2/build
	cd libwebsockets/build/ && rm -rf * && cd ../../
	cd libssh2/build/ && rm -rf * && cd ../../
	#cd libhttp/ ;make clean ; cd ..

compile:
	cd libwebsockets/build/ ;make DEBUG=1 ; cd ../../
	cd libssh2/build/ ;make DEBUG=1 ; cd ../../
	#cd libhttp/ ;make DEBUG=1 ; cd ../

release:
	cd libwebsockets/build/ ;make DEBUG=0 ; cd ../../
	cd libssh2/build/ ;make DEBUG=0 ; cd ../../
	#cd libhttp/ ;make DEBUG=0 ; cd ..

