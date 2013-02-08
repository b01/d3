/**
* This script parses Battle.net URLs to obtain the stub JSON for active/passice hero skills.
* Thus making it a bit easier to maintain them since there is no API route.
* Simply give it a skill URL and is will parse the HTML and output the JSON. May need tweaks as time goes on.
* Example URL: http://us.battle.net/d3/en/class/barbarian/passive/
*/

/**
* Battle.net HTML Skills parser.
*
* @param jQuery Element being parsed.
* @param int p_i Index of current element being parsed.
* @param int p_count Number of elements to parse.
* @param string p_nameSelector CSS selector for the skill name.
* @param string p_descSelector CSS selector of the skill description.
*
* @return string
*/
function parser( $p_that, p_i, p_count, p_nameSelector, p_descSelector, p_runes )
{
    var name = $p_that.find( p_nameSelector ).text().trim().toLowerCase().replace( /-/, '' ).replace( / /g, '-' ),
		$desc = $p_that.find( p_descSelector ),
        desc = '';
		if ( $desc.length > 1 )
		{
			$desc.each(function ()
			{
				desc += $( this ).text().trim().replace( "\n", '' );
			});
		}
		else
		{
			desc = $desc.text().trim();
		}
        seperator = ( p_i < p_count ) ? ',' : '',
        digitRegEx = new RegExp( "[^+?\\d+.?%?]+", 'g' );

    returnValue = "\n\t\"" + name + "\": \{";

    if ( desc.length > 0 )
    {
        returnValue += "\n\t\t\"desc\": \"" + desc + '",';
        returnValue += "\n\t\t\"unknown\": \"" + desc.replace(digitRegEx, " ") + '"';

    }
	if ( p_runes )
	{
		returnValue += ",\n\t\t\"runes\": " + getRuneSkills( name );
	}
    returnValue += "\n\t\}" + seperator;
    return returnValue;
}

/**
*
*/
function getSkillHtml( p_url, p_callBack )
{
	$.ajax( "get-skills-html.php?urlPath=" + p_url, {
		"dataType": "html",
		"success": p_callBack
	});
}

/**
* Parse Passive Skills.
* Find and format passive skills into JSON stub.
* http://us.battle.net/d3/en/class/barbarian/passive/
*/
function parseSkills( $p_skill, p_name, p_desc, p_runes )
{
	var jsonString = '{',
		$skills = $p_skill,
		skillsCount = $skills.length - 1;

	$skills.each(function (i)
	{
		var $this = $( this );
		jsonString += parser( $this, i, skillsCount, p_name, p_desc, p_runes );
	});

	jsonString += "\n}";

	return jsonString;
}

/**
* Get Rune Skills.
* http://us.battle.net/d3/en/class/barbarian/active/{p_name}
*
* @return string
*/
function getRuneSkills( p_name )
{
	setTimeout( function ()
	{
		getSkillHtml( window.heroClass + "/active/" + p_name, function ( p_data )
		{
			$skillsElement = $( $.parseHTML(p_data) ).find( ".rune-details" );
			if ( $skillsElement.length > 0 )
			{
				$( '.' + p_name ).empty().append( parseSkills($skillsElement, ".subheader-3", ".rune-desc", false) );
			}
		});
	}, 2000 );
	return "<pre class=\"" + p_name + "\">null</pre>";
}

jQuery( document ).ready(function ()
{
	if ( typeof window["heroClass"] === "string" && window["heroClass"].length > 1 )
	{
		getSkillHtml( window.heroClass + "/active/", function ( p_data )
		{
			$skillsElement = $( $.parseHTML(p_data) ).find( ".skill-details" );
			if ( $skillsElement.length > 0 )
			{
				var json = parseSkills( $skillsElement, ".subheader-3 a", ".skill-description p", true );
				$( ".pre" ).append( "{\n\"active\": " + json );
				// Now get the passive skills.
				getSkillHtml( window.heroClass + "/passive/", function ( p_data )
				{
					$skillsElement = $( $.parseHTML(p_data) ).find( ".skill-details" );
					if ( $skillsElement.length > 0 )
					{
						var json = parseSkills( $skillsElement, ".subheader-3 a", ".skill-description p", false );
						$( ".pre" ).append( ",\n\n\"passive\": " + json + "\n}");
					}
				});
			}
		});
	}

	/**
	* Parse Skill Runes
	* Find and format active skill runes into JSON stub.
	* http://us.battle.net/d3/en/class/barbarian/active/bash
	*/
	// var jsonString = '{',
		// $runes = $( ".rune-details" ),
		// runesCount = $runes.length - 1;

	// $runes.each(function (i)
	// {
		// var $this = $( this );
		// jsonString += parser( $this, i, runesCount, ".subheader-3", ".rune-desc", false );
	// });
	// jsonString += "\n}";
	// console.log( jsonString );


	/**
	* Parse Active Skills
	* Find and format active skill runes into JSON stub.
	* http://us.battle.net/d3/en/class/barbarian/active/bash
	*/
	// var jsonString = '{',
		// $activeSkill = $( ".skill-details" ),
		// activeSkillCount = $activeSkill.length - 1;

	// $activeSkill.each(function (i)
	// {
		// var $this = $( this );
		// jsonString += parser( $this, i, activeSkillCount, ".subheader-3 a", ".skill-description p", true );
	// });
	// jsonString += "\n}";
	// console.log( jsonString );
});