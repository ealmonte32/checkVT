// ealmonte32
// v:chrome

chrome.contextMenus.create({
	"id": "id_checkVT",
	"title": "Send to checkVT",
	"contexts": ["selection", "link"]
});

chrome.contextMenus.onClicked.addListener(function(item, tab) {
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
	  chrome.tabs.create({url: url, index: tab.index + 1});
  	} else {
  	let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.selectionText;
  	chrome.tabs.create({url: url, index: tab.index + 1});
  	}
  }

  // if the item right clicked is a link
  if (item.linkUrl){
  	let uri = item.linkUrl;
  	let uri_decode = decodeURIComponent(uri); //decode URI
  	item.linkUrl = uri_decode;
	let url = 'http://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.linkUrl;
	chrome.tabs.create({url: url, index: tab.index + 1});
	}

});
