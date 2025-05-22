// ealmonte32
// v:mozilla.firefox

// declare variables
var google_query;
var parsed_url;
var parsed_url_query;
var uri;
var uri_decode;
var url_scheme;
var url_address;
var url_query_match;

// main
browser.contextMenus.create({
    "id": "id_checkVT",
    "title": "Send to checkVT",
    "contexts": ["selection", "link"]
});

browser.contextMenus.onClicked.addListener(function (item, tab) {
    "use strict";

    // if both a link and plaintext selection were detected, we remove the plaintext
    if ((item.linkUrl) && (item.selectionText)) {
        delete item.selectionText;
    }

    // if the item right clicked is plaintext
    if (item.selectionText) {
        uri = item.selectionText;
        uri_decode = decodeURIComponent(uri); //decode URI
        item.selectionText = uri_decode;

        // if the selected text does not contain an http/https scheme, add one
        if (!(/^http:\/\//.test(item.selectionText)) && !(/^https:\/\//.test(item.selectionText))) {
            url_scheme = 'http://';
            parsed_url = url_scheme.concat(item.selectionText);
            url_address = 'https://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + parsed_url;
            browser.tabs.create({url: url_address, index: tab.index + 1});
        } else {
            url_address = 'https://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.selectionText;
            browser.tabs.create({url: url_address, index: tab.index + 1});
        }
    }

    // if the item right clicked is a link
    if (item.linkUrl) {
        // if url is a google.com search query, take the url= part only
        if ((/^https:\/\/www.google.com(.*)/.test(item.linkUrl)) && ((item.linkUrl).toString().match(/url\=(.*)/g))) {
            google_query = item.linkUrl;
            url_query_match = google_query.toString().match(/url\=(.*)/g);
            parsed_url_query = url_query_match.toString().replace('url=', '');
            item.linkUrl = encodeURIComponent(parsed_url_query); //encode URI
            url_address = 'https://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.linkUrl;
            browser.tabs.create({url: url_address, index: tab.index + 1});
        } else {
            item.linkUrl = encodeURIComponent(item.linkUrl); //encode URI
            url_address = 'https://checkvt.epizy.com/checkvtprocess.php?incoming_url=' + item.linkUrl;
            browser.tabs.create({url: url_address, index: tab.index + 1});
        }
    }
});
