CREATE TABLE IF NOT EXISTS `eyez_servers` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `ip` varchar(355) NOT NULL,
  `players` int(11) NOT NULL,
  `maxplayers` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `map` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
