# checkVT
checkVT (check Virus Total), simple right-click item for sending URLs directly to be checked against all engines.

### There can be two ways to go about this:
1. Using the API, we would need users to create their own VirusTotal account in order to have their own api key to be used on their browser sending the requests. This would allow a max of 4 requests per minute, which is fine for most people who dont need to scan every single email they get.
2. Using the GUI, it will simply open up another tab on their current browser and automatically plug in the URL domain they selected into the search field. This is much simpler and does not require users to sign up for the account which removes a barrier for users who do not want to create any accounts.

Example for GUI request:
`https://www.virustotal.com/gui/domain/<domain goes here>`

Cons to #2:
Sending the URL with multiple directories example `https://test.example.com/onefolder/twofolder/dir3/badjsfile.js`

This is not working in the GUI based URL because it will need to remove the https:// and then it only takes in the FQDN of "test.example.com"

A better way might be the following:
- Since the GUI/URL method (https://www.virustotal.com/gui/url/) uses sha256 to process the URL, we could convert the URL to the hash first, and then simply send this hash to the path after the "..gui/url/" as a postfix.
- The hash of the URLs need to have the protocol prefix (http:// or https://) and the end of the URL needs to have a forward slash "/" or else the gui/url/ detection does not work.

### Dealing with redirects:

- `<dealing with redirects here>` / bash scripting currently being tested as example

- Remember some phishing happens with URL shortners to hide the malware file, for example `data:text/html,<script>window.location.href='https://bit.ly/3fNdafO';</script>` this whole line can be used as an href in HTML and since it does not directly begin with the HTTP/S protocol, some services don't recognize it as an actual URL.
