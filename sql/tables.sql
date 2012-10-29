delimiter $$

CREATE TABLE `d3_profiles` (
  `battle_net_id` varchar(200) NOT NULL,
  `profile_json` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`battle_net_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Diablo 3 character profiles imported from battle.net.'$$

delimiter $$

CREATE TABLE `battlenet_api_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `battle_net_id` varchar(45) NOT NULL,
  `url` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `date_number` varchar(45) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Daily log of request made to Battle.net web API.'$$

