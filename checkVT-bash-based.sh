#!/bin/bash
#ealmonte

####################################################################################################
# example finalized URL:
# https://www.virustotal.com/gui/url/805a94723aec052d53b4c2a61d6268ad7dae71c696477cc3e0a524496bc99063/detection
#
# URLs require http(s)
#
# sha256 of URL must contain http:// or https:// and one final / at end
#
# curl arguments:
# -m is for max time to spend before exiting
# -s is for silent
# -k is for allowing connections to SSL sites without certs
#
# for debugging, log file is ~/Desktop/checkvt.log
####################################################################################################

# virus total base URL
VTBASEURL='https://www.virustotal.com/gui/url/'

# get input of URL to check
read -p "Enter the URL to check: " REGURL

# debugging
echo "The entered URL was: ${REGURL}" >> ~/Desktop/checkvt.log

if [[ "${REGURL: -1}" != *'/'* ]] # this checks the end of a string for /
then
	# add / at end of REGURL
	#REGURL="${REGURL}"'/'
	REGURL+='/'
	echo "The entered URL did not contain a / at end, so it was added." >> ~/Desktop/checkvt.log
fi

if [[ "${REGURL}" != 'http:'* ]] || [[ "${REGURL}" != 'https:'* ]]
then
	# add http at beginning of REGURL
	REGURL="http://${REGURL}"
	echo "The entered URL did not contain http or https." >> ~/Desktop/checkvt.log
	echo "http has been added, url is now: ${REGURL}" >> ~/Desktop/checkvt.log
fi


# check REGURL for redirect:
CHECKURL=$(curl -m 2 -sk --head "${REGURL}" | grep -i location | awk '{print $2}')

if [[ -z "${CHECKURL}" ]] # if checkurl is empty, it means no redirect location was grepped
then
	#debug
	echo "(then statement): the checked url does not contain any redirect." >> ~/Desktop/checkvt.log

	# no redirect exist, so use REGURL
	# we must sha256 the entered URL
	SHAURL=$(echo -n "${REGURL}" | shasum -a 256 | awk '{print $1}')

	# then we combine it with the virus total base URL
	FINALURL="${VTBASEURL}${SHAURL}"

	# then we open the combined final URL
	echo "The non-redirected final URL was: ${FINALURL}" >> ~/Desktop/checkvt.log
	open "${FINALURL}"

else
	#redirect location exists
	#debug
	echo "(else statement): the checked url variable contains a redirect to: ${CHECKURL}" #>> ~/Desktop/checkvt.log

	CHECKURL+='/'
	echo "The new redirected URL is now ${CHECKURL}" #>> ~/Desktop/checkvt.log

	#if [[ "${URLREDIRECT: -1}" != *"/"* ]] # if redirected url does not contain / at end, we need to add it
	#then
	#	URLREDIRECT="${URLREDIRECT}"'/'
	#	echo -n "The redirect URL did not contain a / at end." >> ~/Desktop/checkvt.log
	#	echo -n "Fixed, redirect URL is now: ${URLREDIRECT}" >> ~/Desktop/checkvt.log
	#fi

	# redirect exist, so use the redirected URL which is inside the CHECKURL variable already
	SHAURL=$(echo -n "${CHECKURL}" | shasum -a 256 | awk '{print $1}')

	# then we combine it with the virus total base URL
	FINALURL="${VTBASEURL}${SHAURL}"

	# then we open the combined final URL
	echo "The redirected final URL was: ${FINALURL}" >> ~/Desktop/checkvt.log
	#open "${FINALURL}"

fi

echo "########################################################" >> ~/Desktop/checkvt.log

#end