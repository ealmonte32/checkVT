#!/bin/bash
#ealmonte

####################################################################################################
# example finalized URL:
# https://www.virustotal.com/gui/url/805a94723aec052d53b4c2a61d6268ad7dae71c696477cc3e0a524496bc99063/detection
#
# URLs require http:// or https:// , and must end with / at end
#
# URL must be converted to SHA256
# using shasum: echo -n "http://url.com/" | shasum -a 256 | awk '{print $1}'
# using openssl: echo -n "http://url.com/" | openssl dgst -sha256
#
# curl arguments:
# -m is for max time in seconds to spend before exiting
# -s is for silent
# -k is for allowing connections to SSL sites without certs
# -I is for retrieving header info only
#
# for debugging, echo to the log file ~/Desktop/checkvt.log
#
# someone suggested naming variables with underscore "_" in the beginning of the variable name in order
# to never conflict with built-in/pointers names that interpreters have
####################################################################################################

#enable/disable for line by line debugging
set -x

# VirusTotal base URL
_VTBASEURL=https://www.virustotal.com/gui/url/

# get input of URL to check
read -p "Enter the URL to check: " _REGURL

echo "The entered URL was: ${_REGURL}" >> ~/Desktop/checkvt.log

function checkforendingslash {
#if [[ "${_REGURL: -1}" != '/' ]] # this checks the end of a string for /
if [[ "${_REGURL}" != *'/' ]] 
then
	# add / at end of REGURL
	_REGURL+='/'
	echo "The entered URL did not contain a / at end, so it was added." >> ~/Desktop/checkvt.log
fi
}

function checkforhttp {
if [[ "${_REGURL}" != *'http:'* && "${_REGURL}" != *'https:'* ]]
then
	# add http at beginning of REGURL
	_REGURL="http://${_REGURL}"
	echo "The entered URL did not contain http or https." >> ~/Desktop/checkvt.log
	echo "http has been added, url is now: ${_REGURL}" >> ~/Desktop/checkvt.log
fi
}

checkforhttp;

# check REGURL for redirect:
_CHECKURL=$(curl -m 3 -skI "$_REGURL" | awk '/^[Ll]ocation:/ {sub("\r", "", $2); print $2}')
# the /^[Ll]ocation:/" part makes it only print the Location (or "location") header
# and sub("\r", "", $2) deletes the carriage return from $2 before it's printed


if [[ -z "${_CHECKURL}" ]] # if checkurl is empty, it means no redirect location was grepped
then
	echo "(no redirect): the checked url does not contain any redirect." >> ~/Desktop/checkvt.log

	# no redirect exist, so use REGURL
	# we must sha256 the entered URL
	_SHAURL=$(echo -n "${_REGURL}" | openssl dgst -sha256)

	# then we combine it with the virus total base URL
	_FINALURL="${_VTBASEURL}${_SHAURL}"

	# then we open the combined final URL
	echo "The non-redirected final URL was: ${_FINALURL}" >> ~/Desktop/checkvt.log
	open "${_FINALURL}"

else
	echo "(redirect found): the checked url variable contains a redirect to: ${_CHECKURL}" >> ~/Desktop/checkvt.log
	echo "The new redirected URL is now ${_CHECKURL}" >> ~/Desktop/checkvt.log

	if [[ "${_CHECKURL}" != *'/' ]] # if redirected url does not contain / at end, we need to add it
	then
		_CHECKURL="${_CHECKURL}/"
		echo -n "The redirect URL did not contain a / at end." >> ~/Desktop/checkvt.log
		echo -n "Fixed, redirect URL is now: ${_CHECKURL}" >> ~/Desktop/checkvt.log
	fi

	# redirect exist, so use the redirected URL which is inside the CHECKURL variable already
	_SHAURL=$(echo -n "${_CHECKURL}" | openssl dgst -sha256)

	# then we combine it with the virus total base URL
	_FINALURL="${_VTBASEURL}${_SHAURL}"

	# then we open the combined final URL
	echo "The redirected final URL was: ${_FINALURL}" >> ~/Desktop/checkvt.log
	open "${_FINALURL}"

fi


echo "######################## Last run on: $(date) ########################" >> ~/Desktop/checkvt.log

#end