// ealmonte32
// v:firefox

browser.contextMenus.create({
	"id": "id_checkVT",
	"title": "Send to checkVT",
	"contexts": ["selection", "link"]
});

browser.contextMenus.onClicked.addListener(function(item, tab) {
"use strict";

  // if both a link and plaintext selection were detected, we remove the plaintext
  if ((item.linkUrl) && (item.selectionText)){
    delete item.selectionText;
	}
  
  // if the item right clicked is plaintext
  if (item.selectionText){
  	let uri = item.selectionText;
  	let uri_decode = decodeURIComponent(uri); //decode URI
  	item.selectionText = uri_decode;
  	
  	// if the selected text does not contain an http/https scheme, add one
  	if (!/^http:\/\//.test(item.selectionText) && !/^https:\/\//.test(item.selectionText)){
  	  let url_scheme = 'http://';
  	  let parsed_url = url_scheme.concat(item.selectionText);
  	  let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + parsed_url;
	  browser.tabs.create({url: url, index: tab.index + 1});
  	} else {
  	let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.selectionText;
  	browser.tabs.create({url: url, index: tab.index + 1});
  	}
  }

  // if the item right clicked is a link
  if (item.linkUrl){
  	// if url is a google.com search query, take the url= part only
  	if (/^https:\/\/www.google.com(.*)/.test(item.linkUrl)){
  	  let google_query = item.linkUrl;
  	  console.log('debug: ' + google_query);
  	  let url_query_match = google_query.match(/url=(.*)/);
	  let parsed_url_query = url_query_match.toString().replace('url=', '');
  	  console.log('debug: ' + parsed_url_query);
  	  let uri = parsed_url_query;
  	  let uri_decode = decodeURIComponent(uri); //decode URI
  	  item.linkUrl = uri_decode;
	  let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.linkUrl;
	  browser.tabs.create({url: url, index: tab.index + 1});
  	} else {
  		let uri = item.linkUrl;
  		let uri_decode = decodeURIComponent(uri); //decode URI
  		item.linkUrl = uri_decode;
		let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.linkUrl;
		browser.tabs.create({url: url, index: tab.index + 1});
		}
	}
});
