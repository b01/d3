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
function parser( $p_that, p_i, p_count, p_nameSelector, p_descSelector )
{
    var name = $p_that.find( p_nameSelector ).text().trim().toLowerCase().replace( / /g, '-' ),
		$desc = $p_that.find( p_descSelector ),
        desc = '';
		if ( $desc.length > 1 )
		{
			$desc.each(function ()
			{
				desc += $( this ).text().trim();
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
    returnValue += "\n\t\}" + seperator;
    return returnValue;
}

/**
* Parse Passive Skills.
* Find and format passive skills into JSON stub.
* http://us.battle.net/d3/en/class/barbarian/passive/
*/
var jsonString = '{',
    $passiveSkills = $( ".skill-details" ),
    passiveSkillsCount = $passiveSkills.length - 1;

$passiveSkills.each(function (i)
{
    var $this = $( this );
    jsonString += parser( $this, i, passiveSkillsCount, ".subheader-3 a", ".skill-description p" );
});

jsonString += "\n}";
console.log( jsonString );


/**
* Parse Skill Runes
* Find and format active skill runes into JSON stub.
* http://us.battle.net/d3/en/class/barbarian/active/bash
*/
var jsonString = '{',
	$runes = $( ".rune-details" ),
    runesCount = $runes.length - 1;

$runes.each(function (i)
{
    var $this = $( this );
	jsonString += parser( $this, i, runesCount, ".subheader-3", ".rune-desc" );
});
jsonString += "\n}";
console.log( jsonString );


/**
* Parse Skill Runes
* Find and format active skill runes into JSON stub.
* http://us.battle.net/d3/en/class/barbarian/active/bash
*/
var jsonString = '{',
	$activeSkill = $( ".skill-details" ),
    activeSkillCount = $activeSkill.length - 1;

$activeSkill.each(function (i)
{
    var $this = $( this );
	jsonString += parser( $this, i, activeSkillCount, ".subheader-3 a", ".skill-description p" );
});
jsonString += "\n}";
console.log( jsonString );