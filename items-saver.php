<!DOCTYPE html>
<html>
<?php

	print_r( $_POST );
	$item = $_POST;
	$effectNames = $item['effectNames'];
	$effectValues = $item['effectValues'];
?>
<body>
<pre>
{
   "id":"Generated DB ID",
   "name":"Intrepid Witness",
   "icon":"spiritstone_204_demonhunter_male",
   "displayColor":"yellow",
   "tooltipParams":null,
   "requiredLevel":60,
   "itemLevel": <?= $item['level'] ?>,
   "bonusAffixes":0,
   "typeName":"<?= $item['class'] ?> Spirit Stone",
   "type":{
      "id":"SpiritStone_Monk",
      "twoHanded":false
   },
   "armor":{
      "min": <?= $item['armor'] ?>,
      "max": <?= $item['armor'] ?>
   },
   "attributes":[
      "+56 Dexterity",
      "+78 Vitality",
      "+43 Physical Resistance",
      "+7% Life",
      "+327 Armor",
      "Increases Sweeping Wind Damage by 7% (Monk Only)"
   ],
   "attributesRaw":{
	<?php //for( $i = 0; $i < count($effectNames); $i++ ): ?>

      "Power_Damage_Percent_Bonus#Monk_SweepingWind":{
         "min":0.07,
         "max":0.07
      },
	<?php //endfor; ?>
      "Power_Damage_Percent_Bonus#Monk_SweepingWind":{
         "min":0.07,
         "max":0.07
      },
      "Armor_Bonus_Item":{
         "min":327.0,
         "max":327.0
      },
      "Dexterity_Item":{
         "min":56.0,
         "max":56.0
      },
      "Resistance#Physical":{
         "min":43.0,
         "max":43.0
      },
      "Armor_Item":{
         "min":397.0,
         "max":397.0
      },
      "Vitality_Item":{
         "min":78.0,
         "max":78.0
      },
      "Durability_Max":{
         "min":392.0,
         "max":392.0
      },
      "Durability_Cur":{
         "min":391.0,
         "max":391.0
      },
      "Hitpoints_Max_Percent_Bonus_Item":{
         "min":0.07,
         "max":0.07
      }
   },
   "socketEffects":[

   ],
   "gems":[

   ]
}
</pre>
</body>
</html>