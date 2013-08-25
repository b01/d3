/*global window: false, document: false, setTimeout: false, ajaxRequest: false, jQuery: false, $: false, convertPercentToDecimal: true */
"use strict";

/**
* Get active/passive skills for a particular hero class.
*
* example url = "/get-url.php?which=skill-2&class={class}&slug={slug-name}
*
* @param string pUrl Url to retrieve hero data from.
* @param jQuery pContainer Url
* @param function pDoneCallback executed once the request is done.
*
* @return bool
*/
function getBattleNetPage(pUrl, $pContainer, pDoneCallback) {
	if (typeof pUrl !== "string" || pUrl.length === 0 || typeof pDoneCallback !== "function") {
		return false;
	}

	ajaxRequest({
		"url": pUrl,
		"dataType": "html",
		"done": pDoneCallback,
		"context": $pContainer
	});

	return true;
}

/**
* Battle.net HTML Skills parser.
*
* @param jQuery $p_that Element being parsed.
* @param int p_i Index of current element being parsed.
* @param int p_count Number of elements to parse.
* @param string p_nameSelector CSS selector for the skill name.
* @param string p_descSelector CSS selector of the skill description.
* @param string p_tabs number of tabs to indent.
*
* @return string
*/
function parseSkills($p_that, p_i, p_count, p_nameSelector, p_descSelector, p_tabs) {
    var name = $p_that.find(p_nameSelector).text().trim().toLowerCase().replace(/-/, '').replace(/ /g, '-'),
		$desc = $p_that.find(p_descSelector),
        desc = '',
        seperator = (p_i < p_count) ? ',' : '',
        matchRegEx = /\+?\d+(\.\d+)?(-|\s-\s)?\d*%?/g,
		spaceRegEx = /\r\n|\n|\s{2,}/g,
		replaceRegEx = /(\+?\d+\.?\d*%|\+?\d+(-|\s-\s)\d+)/g,
		tabs = p_tabs || '',
		returnValue,
		numbers;

	if ($desc.length > 1) {
		$desc.each(function () {
			desc += $(this).text().trim().replace(spaceRegEx, ' ');
		});
	} else {
		desc = $desc.text().trim().replace(spaceRegEx, ' ');
	}

    returnValue = "\n" + tabs + "\t\t\"<span class=\"slug\">" + name + "</span>\": {<span class=\"" + name + "\">";

    if (desc.length > 0) {
        returnValue += "\n" + tabs + "\t\t\t\"desc\": \"" + desc + '"';
		numbers = desc.match(matchRegEx, ",\n" + tabs + "\t\t\t" + "\"unknown\": ");
		if (numbers !== null && numbers.length > 0) {
			returnValue += ",\n" + tabs + "\t\t\t\"unknown\": " + numbers.join(",\n" + tabs + "\t\t\t\"unknown\": ")
				.replace(replaceRegEx, "\"$1\"");
			returnValue = convertPercentToDecimal(returnValue);
		}

    }
    returnValue += "<span class=\"trail\">\n" + tabs + "\t\t</span></span>}" + seperator;
    return returnValue;
}

/**
* Queue HTTP request if one is already in process.
* In combination with ajaxDone, will queue an HTTP
* request then process them in the order they were
* received. Only allowing one at a time.
*
* @param object pObject Object suitable for jQuery.ajax.
* @return bool
*/
function ajaxRequest(pObject) {
	var queue = window.ajaxQueue,
		currentRequest = window.currentAjaxRequest;

	if (typeof pObject !== "object") {
		return false;
	}

	if (currentRequest === null) {
		window.currentAjaxRequest = $.ajax(pObject);
		// Promise methods that need to be attached to the jXHR object after it is instantiated.
		if (pObject.hasOwnProperty('done')) {
			window.currentAjaxRequest.done(pObject.done);
		}

		if (pObject.hasOwnProperty('fail')) {
			window.currentAjaxRequest.fail(pObject.fail);
		}

		if (pObject.hasOwnProperty('always')) {
			window.currentAjaxRequest.always(pObject.always);
		}
	} else {
		queue.push(pObject);
	}

	return true;
}

/**
* Global jQuery.ajaxComplete method.
*/
function ajaxDone() {
	var queue = window.ajaxQueue;

	window.currentAjaxRequest = null;

	if (queue.length > 0) {
		ajaxRequest(queue.pop());
	}
}

/**
* Get Rune Skills.
* http://us.battle.net/d3/en/class/barbarian/active/{slug-name}
*
* @param string pClass
* @param string pName
* @return bool
*/
function getRuneSkills(pClass, $pName) {

	if (typeof pClass !== "string" || pClass.length === 0 || $pName.length === 0) {
		return false;
	}

	$pName.each(function () {
		var slug = $(this).text(),
			url = "get-url.php?which=skill-2&class=" + pClass + "&slug=" + slug;

		ajaxRequest({
			"url": url,
			"dataType": "html",
			"done": function (p_data) {
				var jsonString = ",\t\n\t\t\t\"runes\": {",
					$runes = $($.parseHTML(p_data)).find(".rune-details"),
					runeCount = $runes.length - 1,
					$slug;

				if ($runes.length > 0) {
					$runes.each(function (i) {
						var $this = $(this);
						jsonString += parseSkills($this, i, runeCount, ".subheader-3", ".rune-desc", "\t\t");
					});
					jsonString += "\n\t\t\t}\n\t\t";
					$slug = $('.' + slug);
					$slug.find(".trail").remove();
					$slug.append(jsonString);
				}
			}
		});
	});

	return true;
}

/**
* Parse skills from HTTP request.
*
* @param string pData Response text from URL request.
*
*/
function ajaxSuccess(pData) {
	var jsonString = "{",
		$skills = $($.parseHTML(pData)).find(".skill-details"),
		skillsCount;

	if ($skills.length > 0) {
		skillsCount = $skills.length - 1;

		$skills.each(function (i) {
			var $this = $(this);
			jsonString += parseSkills($this, i, skillsCount, ".subheader-3 a", ".skill-description p");
		});

		jsonString = jsonString + "\n\t}";

		$(this).append(jsonString);
	}
}

/**
* Replace percent values in a string with their decimial equivalent.
*
* @param string pPercentString String to be parsed for percent values.
* @return string String with percent values replaced with decimials.
*/
function convertPercentToDecimal(pPercentString) {
	return pPercentString.replace(/"(\d)%"/g, "0.0$1")
		.replace(/"(\d\d)%"/g, "0.$1")
		.replace(/"(\d)(\d\d)%"/g, "$1.$2");
}

/**
* This script parses Battle.net URLs to obtain the stub JSON for active/passice hero skills.
* Thus making it a bit easier to maintain them since there is no API route.
* Simply give it a skill URL and is will parse the HTML and output the JSON. May need tweaks as time goes on.
* Example URL: http://us.battle.net/d3/en/class/barbarian/passive/
*/

jQuery(document).ready(function () {
	window.ajaxQueue = [];
	window.currentAjaxRequest = null;
	$(document).ajaxComplete(ajaxDone);

	if (typeof window.heroClass === "string" && window.heroClass.length > 1) {
		getBattleNetPage("get-url.php?which=skill-1&class=" + window.heroClass, $(".active"), ajaxSuccess);

		getBattleNetPage("get-url.php?which=skill-3&class=" + window.heroClass, $(".passive"), ajaxSuccess);

		setTimeout(function () {
			getRuneSkills(window.heroClass, $(".active .slug"));
		}, 2000);
	}
});