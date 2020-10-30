# checkVT
checkVT (check Virus Total):

An simple web browser extension that takes users URLs submitted via right-click and sends them directly to be checked against all engines on VirusTotal.

Alternatively, users can use the web-based version to manually submit URLs: http://checkvt.epizy.com/

### There are two ways to do this process in the backend:
1. Using the extension with the VirusTotal [API](https://developers.virustotal.com/reference), we would need users to create their own VirusTotal account in order to have their own api key to be used on their browser sending the requests. This would allow a max of 4 requests per minute, which is fine for most people who dont need to scan every single link they get or feel like checking.
2. Using the extension without the API and instead using the GUI, it will simply open up another tab on their current browser and automatically plug in the URL domain they selected into the search field. This is much simpler and does not require users to sign up for the account which removes a barrier for users who do not want to create any accounts.
3. Using a web-app running php on the backend, we simply let users manually enter the URLs for processing which performs the extra effective URL action that VirusTotal does not do, which essentially means that if the HTTP headers or redirects on the website have a final destination (which malicious URLs usually do), this sends whatever final destination URL it was able to find.



### Limitations:

- There is a very simple but effective way that some malicious websites hide the final destination URL they want you to land on, which `cURL` cannot retrieve, and that is the use of the javascript `<script>document.location.href` method. By using this method on their website, they essentially bypass the one aspect that `cURL` is good for, which is being able get HTTP headers, check, and view source code of websites without actually running javascript code on your machine. Javascript is one of the major ways malware, ransomware, viruses, and other malicious code finds its way onto your machine.

- Some phishing happens with URL shortners to hide the malware file, for example `data:text/html,<script>window.location.href='https://bit.ly/3fNdafO';</script>` this whole line can be used as an href in HTML and since it does not directly begin with the HTTP/S protocol, most services don't recognize it as an actual URL.
