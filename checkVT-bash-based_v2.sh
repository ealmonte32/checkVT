#!/bin/bash
#ealmonte

####################################################################################################
# example finalized URL:
# https://www.virustotal.com/gui/url/805a94723aec052d53b4c2a61d6268ad7dae71c696477cc3e0a524496bc99063/detection
#
# this URL is the analysis one but cannot currently figure out how to make it work for urls not on VT database already:
# https://www.virustotal.com/gui/url-analysis/u-e35cdd5388c96070a9671fe95ea0df574e0a963c6e3567d51251a7073ddde994-1598144529/detection
# the ending after sha256 is epochtime 
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
# -L is to follow redirects but returns multiple header results so we cannot use it
#
# curl command: curl -m 5 -skI http://example.com | awk '/^[Ll]ocation:/ {sub("\r", "", $2); print $2}'
# better curl command to use: curl -Ls -o /dev/null -w %{url_effective} http://example.com
#
# for debugging, echo to log file of current directory:
# _LOGFILE="$(pwd)/checkvt.log"
#
# it is best practice to name variables with underscore "_" in the beginning of the variable name in order
# to never conflict with built-in/pointers names that interpreters have 
#
#
# a note for the awk part:
# the /^[Ll]ocation:/" part makes it only print the "Location" or "location" header
# and sub("\r", "", $2) deletes the carriage return from $2 before it's printed
####################################################################################################

#line by line debugging, turn on: set -x, turn off: set +x
set -x

# set current working directory
cd "$(dirname "${0}")"

# set location of log file to inside current working dir
_LOGFILE="$(pwd)/checkvt.log"

# VirusTotal base URL
_VTBASEURL='https://www.virustotal.com/gui/url/'

# epochtime when testing of analysis url
#_EPOCHTIME="-$(date +%s)"

# get input of URL to check
read -p "Enter the URL to check: " _REGURL
echo "The entered URL was: ${_REGURL}" >> "${_LOGFILE}"

# check if the URL doesnt have http or https protocol
# ** we use && and not the OR equivalent of || because if it doesnt contain http and it contains https
# ** then the system thinks that its either or, when it is actually both conditions that must be met
if [[ "${_REGURL}" != *'http:'* && "${_REGURL}" != *'https:'* ]]
then
	# add http at beginning of REGURL because almost all https enforced sites will redirect from their http
	_REGURL="http://${_REGURL}"
	echo "The entered URL did not contain http or https." >> "${_LOGFILE}"
	echo "http has been added, url is now: ${_REGURL}" >> "${_LOGFILE}"
fi

# check the entered URL and get the final landing URL:
_CHECKURL=$(curl -Ls -o /dev/null -w %{url_effective} "$_REGURL")

	# if the URL does not contain http or https, we need to add it
	if [[ "${_CHECKURL}" != *'http:'* && "${_CHECKURL}" != *'https:'* ]]
	then
		# add http at beginning of REGURL because almost all https enforced sites will redirect from their http
		_CHECKURL="http://${_CHECKURL}"
		echo "The checked URL did not contain http or https." >> "${_LOGFILE}"
		echo "http has been added, URL is now: ${_CHECKURL}" >> "${_LOGFILE}"
	fi

	# we must sha256 the entered URL
	_SHAURL=$(echo -n "${_CHECKURL}" | openssl dgst -sha256)

	# then we combine it with the virus total base URL
	_FINALURL="${_VTBASEURL}${_SHAURL}"

	# then we open the combined final URL
	echo "The non-redirected final URL was: ${_FINALURL}" >> "${_LOGFILE}"
	open "${_FINALURL}"

echo "######################## Last run on: $(date) ########################" >> "${_LOGFILE}"

#end