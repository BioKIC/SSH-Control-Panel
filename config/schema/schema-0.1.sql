CREATE TABLE `portaldb` (
  `portalID` int(11) NOT NULL AUTO_INCREMENT,
  `projectName` varchar(45) DEFAULT NULL,
  `projectDescription` varchar(500) DEFAULT NULL,
  `projectLead` varchar(45) DEFAULT NULL,
  `projectLeadEmail` varchar(45) DEFAULT NULL,
  `projectUrl` varchar(250) DEFAULT NULL,
  `tcnUrl` varchar(250) DEFAULT NULL,
  `projectStatus` varchar(45) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `geoScope1` varchar(45) DEFAULT NULL,
  `geoScope2` varchar(45) DEFAULT NULL,
  `taxonScope1` varchar(45) DEFAULT NULL,
  `taxonScope2` varchar(45) DEFAULT NULL,
  `serverName` varchar(45) DEFAULT NULL,
  `schemaName` varchar(45) DEFAULT NULL,
  `lastStatTimestamp` datetime DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `initialTimestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`portalID`),
  UNIQUE KEY `UQ_portal_name` (`projectName`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `frontend` (
  `frontendID` int(11) NOT NULL AUTO_INCREMENT,
  `portalID` int(11) DEFAULT NULL,
  `acronym` varchar(45) DEFAULT NULL,
  `title` varchar(75) NOT NULL,
  `frontendStatus` varchar(45) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `scope` varchar(45) DEFAULT NULL,
  `manager` varchar(45) DEFAULT NULL,
  `managerEmail` varchar(45) DEFAULT NULL,
  `primaryLead` varchar(45) DEFAULT NULL,
  `primaryLeadEmail` varchar(45) DEFAULT NULL,
  `portalUrl` varchar(250) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `initialTimestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`frontendID`),
  UNIQUE KEY `UQ_frontend_name` (`title`),
  UNIQUE KEY `UQ_frontend_acronym` (`acronym`),
  KEY `FK_frontend_portalID_idx` (`portalID`),
  CONSTRAINT `FK_frontend_portalID` FOREIGN KEY (`portalID`) REFERENCES `portaldb` (`portalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) NOT NULL,
  `institution` varchar(200) DEFAULT NULL,
  `department` varchar(200) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `guid` varchar(45) DEFAULT NULL,
  `validated` varchar(45) NOT NULL DEFAULT '0',
  `initialTimeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  UNIQUE KEY `UQ_user_email` (`email`,`lastName`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `userlogin` (
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(45) NOT NULL,
  `pwd` varchar(45) NOT NULL,
  `alias` varchar(45) DEFAULT NULL,
  `lastlogindate` datetime DEFAULT NULL,
  `initialTimeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`username`) USING BTREE,
  UNIQUE KEY `UQ_userlogin` (`alias`),
  KEY `IX_login_uid` (`uid`),
  CONSTRAINT `FK_login_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `userroles` (
  `userRoleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `role` varchar(45) NOT NULL,
  `tableName` varchar(45) DEFAULT NULL,
  `tablePK` int(11) DEFAULT NULL,
  `secondaryVariable` varchar(45) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `uidAssignedBy` int(10) unsigned DEFAULT NULL,
  `initialTimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`userRoleID`),
  UNIQUE KEY `Unique_userroles` (`uid`,`role`,`tableName`,`tablePK`),
  KEY `FK_userroles_uid_idx` (`uid`),
  KEY `FK_usrroles_uid2_idx` (`uidAssignedBy`),
  KEY `Index_userroles_table` (`tableName`,`tablePK`),
  CONSTRAINT `FK_userrole_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_userrole_uid_assigned` FOREIGN KEY (`uidAssignedBy`) REFERENCES `user` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usertoken` (
  `tokenID` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `token` varchar(50) NOT NULL,
  `device` varchar(50) DEFAULT NULL,
  `initialTimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tokenID`),
  KEY `IX_usertoken_uid` (`uid`),
  CONSTRAINT `FK_usertoken_uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

