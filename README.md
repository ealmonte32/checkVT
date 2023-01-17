<p align="center">
  <img src="https://raw.githubusercontent.com/ealmonte32/checkVT/master/checkVT_logo_icons/checkvt_icon-128x128.png">
</p>

# checkVT
checkVT (check VirusTotal) is an open source project developed by Emyll Almonte for an independent research class supervised by [Dr. Vaibhav Anu](http://vaibhavanu.com/) (Assistant Professor, [Department of Computer Science](https://www.montclair.edu/computer-science/), Montclair State University).

It is a simple, yet useful, web browser addon/extension that takes a selected URL via context-menu and submits it directly to be checked against all engines on VirusTotal with an added feature that VirusTotal doesn't have. This added feature is basically the part of the process that tries to find the effective URL (redirect) if it exists on the URL that was submitted, and sends that URL to VirusTotal rather than the URL that was selected. This extra step increases the chances of allowing the user to see VirusTotal results for the URL host that they would have ended up at, as opposed to the original link.

Browser addon/extension available for [Google Chrome](https://chrome.google.com/webstore/detail/checkvt/fppeaohnnimpokhckkjfdafcpkoibjbp), [Microsoft Edge](https://microsoftedge.microsoft.com/addons/detail/checkvt/njamaljfifcgfeifjjoimemkkbojfhij), and [Mozilla Firefox](https://addons.mozilla.org/en-US/firefox/addon/checkvt/). New releases will be uploaded directly to their respective browser addon store.

Alternatively, users can use the web-based version to manually submit URLs: http://checkvt.epizy.com/

### After brainstorming of possible ways to create this process, these were the most sensible options:
1. Using the browser extension with the VirusTotal [API](https://developers.virustotal.com/reference), we would need users to create their own VirusTotal account in order to have their own API key to be used on their browser sending the requests. This would allow a max of 4 requests per minute, which is fine for most people who dont need to scan every single link they get or feel like checking.
2. Using the browser extension without the API and instead using the GUI, it will simply open up a tab on their current browser and automatically plug in the URL domain they selected into the checkVT search/process field after it has been processed by the [PHP](https://www.php.net/)/[curl](https://curl.se/) function. This is much simpler and does not require users to sign up for the account which removes a barrier for users who do not want to create any account at all and simply want to submit a URL.
3. Using a web-app running php on the backend, we simply let users manually enter the URLs for processing which performs the extra effective URL action that VirusTotal does not do. This requires multiple steps and adds a burden for the user who wants simplicity/ease of use.



### Limitations:
- (Affects v1.0.4 and below) When you use the checkVT browser extension or the web-app, it essentially parses links checking for redirects along the way and uses sha256 hash to create the unique ID of this URL that VirusTotal looks for in their database. The way that checkVT works with the VirusTotal system is that if you submit a URL (which gets converted into its sha256 hash form) and their database does not have a record for this hash being analyzed, it returns the "Item not found" error. The problem here is that a hash is unique and any single character difference in the source will create a completely unique and different hash. So for example if you wanted to submit the URL `http://www.somewebsite.com/user1`, but their system has a record for `http://www.somewebsite.com/user2`, you will get an error because the sha256 hash of `http://www.somewebsite.com/user1` is `4806811b6407cbd545d753d87294889ac537042f4a21c813cba30219acdbf23a` and that of `http://www.somewebsite.com/user2` is `9d2fb06b5f64b4faa8da5e7b27c778c9fa182b1bc2ec3ddecf87ea7bfd3e8425`. As you can see, completely different just by changing user1 to user2.

- (Affects v1.0.4 and below) Since the current version of checkVT process relies on VirusTotal having the URL already analyzed and we sometimes get an "Item not found" error as explained above, I thought that the best way to reach a balance with this issue would be to only submit the link's hostname/domain `http://www.somewebsite.com`, and not everything that goes after it. By choosing this method, we reduce the chances of almost always getting an "Item not found" (URL not analyzed) error, and you still get to see if the domain of the website you are about to click on is potentially unsafe or suspicious. *This limitation of only checking the host/domain will change and improved to full URL search on release of version 2.0.0*.

- There is a very simple but effective way that some malicious websites hide the final destination URL they want you to land on, which `curl` cannot retrieve, and that is the use of the javascript `<script>document.location.href` method. By using this method on their website, they essentially bypass the one aspect that `curl` is good for, which is being able get HTTP headers, check, navigate to redirects, and view source code of websites without actually running javascript code on your machine. Javascript is one of the major ways malware, ransomware, viruses, and other malicious code finds its way onto your machine.

- Some phishing happens by combining URL shortners and unusually formatted links, which they use to hide the malware file, for example `data:text/html,<script>window.location.href='https://bit.ly/3fNdafO';</script>` this whole line can be used as an href in HTML and since it does not directly begin with the HTTP/S protocol, most services don't recognize it as an actual URL. If you test that text by inserting it into your browser, it should send your browser to `https://cdn.example.com/somedir/badfile.js`.


### Release notes:
Version 2.3:
- Minor JavaScript linting fixes
- Minor improvements to reduce lines of code
- Minor URL encoding fixes
- Popup localhost line fix


Version 2.0.0:
- URLs are now submitted in full form of 'scheme' (http/https) + 'host' + 'path'
- URLs being submitted in full rather than 'scheme' + 'host' being checked for previous analyzation provides a greater form of protection by returning a wider range of results
- Unlike previous versions where it just said "Item-Not-Found" for URLs that had not been analyzed, your never-before submitted URL is now automatically sent to be analyzed

Known Issues:
- When a never-before submitted URL is sent through, the VirusTotal on-demand analysis status process might flicker many times and VirusTotal can take minutes to finish analyzing and results wont show until all their engines have finished analyzing the URL
- Users might be prompted to complete a CAPTCHA from VirusTotal on some on-demand scans due to limits of submissions per minute


Version 1.0.4:
- Added checkVT URL search field directly onto add-on/extension popup for quick access
- Improved URL cleaning of whitespace
- New logo


Version 1.0.3:
- Initial public release
- Improved URL decoding
- Added google search result filtering to send the URL after the "url=" string and not "google.com" to process
- Improved detection of a link or a text-based URL when both were selected


Version 1.0.0-1.0.2:
- Initial beta release
- Added option for curl to respect GET requests when following 301, 302, and 303 redirections
- Added HTTP user agent header to processing because some servers act different if the user agent is not supplied
- Added all encoding option to send gzip, deflate, etc on request
- Added parsing of URL scheme

___

### Privacy Policy

There is no personal or user identifiable data transmitted from the user's computer to the database that processes the URL. The selected URL that the user submits to checkVT is the only data that gets stored on the database for reporting and statistical purposes. We store the Original URL Address, the Final URL Destination Address, the VirusTotal hash ID of the URL, and the timestamp of when it was submitted.
