CREATE TABLE IF NOT EXISTS `eyez_servers` (
 `id` int(12) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `port` text NOT NULL,
  `players` text NOT NULL,
  `maxplayers` text NOT NULL,
  `type` text NOT NULL,
  `map` text NOT NULL,
  `hostname` text NOT NULL,
  `status` text NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=11 ;
