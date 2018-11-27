CREATE TABLE IF NOT EXISTS `book` (
  `BookID` int(10) NOT NULL,
  `CategoryID` int(10) NOT NULL,
  `BookTitle` tinytext NOT NULL,
  `ShortDetail` tinytext NOT NULL,
  `Description` mediumtext NOT NULL,
  `PublisherName` tinytext NOT NULL,
  `ISBN10` varchar(10) NOT NULL,
  `ISBN13` varchar(14) NOT NULL,
  `PrimaryAuthorName` tinytext NOT NULL,
  `PublicationYear` smallint(6) NOT NULL,
  `ListPrice` decimal(6,2) NOT NULL,
  `CurrentPrice` decimal(6,2) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `SaleTypeID` int(10) NOT NULL,
  PRIMARY KEY (`BookID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `saletype` (
  `SaleTypeID` int(10) NOT NULL,
  `SaleTypeTitle` tinytext NOT NULL,
  `SaleTypeCssImage` tinytext NOT NULL,
  `SaleTypeCssClass` tinytext NOT NULL,
  PRIMARY KEY (`SaleTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `category` (
  `CategoryID` int(10) NOT NULL,
  `ParentID` int(10) DEFAULT NULL,
  `CategoryTitle` tinytext NOT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50) NOT NULL,
  `Password` tinytext NOT NULL,
  `UserTitle` varchar(110) NOT NULL,
  `UserTypeID` int(10) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `ZipCode` varchar(50) DEFAULT NULL,
  `Address` tinytext,
  `InsertDateTime` datetime NOT NULL,
  `ClientIPAddress` varchar(30) NOT NULL,
  `IsEmailVerified` tinyint(4) NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;



INSERT INTO `book` (`BookID`, `CategoryID`, `BookTitle`, `ShortDetail`, `Description`, `PublisherName`, `ISBN10`, `ISBN13`, `PrimaryAuthorName`, `PublicationYear`, `ListPrice`, `CurrentPrice`, `Quantity`, `SaleTypeID`) VALUES
	(1, 1, 'Canon EOS Rebel T4i / 650D: From Snapshots to Great Shots (DVD) [DVD-ROM] ', 'Owners of the Canon EOS T4i / 650D–the latest in Canon’s popular Rebel series (the world’s bestselling DSLR series)–will want to view this video again and again as they master the powerful features of this new camera. ', 'Owners of the Canon EOS T4i / 650D–the latest in Canon’s popular Rebel series (the world’s bestselling DSLR series)–will want to view this video again and again as they master the powerful features of this new camera. ', 'Peachpit Press; 1 edition (March 11, 2013) ', '0321890493', '978-0321890498', 'Jeff Revell ', 2013, 34.99, 26.19, 1, 3),
	(2, 1, 'David Busch\'s Compact Field Guide for the Canon EOS Rebel T3/1100D [Spiral-bound] ', 'Are you tired of squinting at the tiny color coded tables and difficult-to-read text you find on the typical laminated reference card that you keep with you when you\'re in the field or on location?', 'Are you tired of squinting at the tiny color coded tables and difficult-to-read text you find on the typical laminated reference card that you keep with you when you\'re in the field or on location? Well throw away your cheat sheets and command cards! DAVID BUSCH\'S COMPACT FIELD GUIDE FOR THE CANON EOS REBEL T3/1100D is your solution. This new, lay flat, spiral bound, reference guide condenses all the must-have information you need while shooting into a portable book you\'ll want to permanently tuck into your camera bag. You\'ll find every settings option for your Canon EOS Rebel T3/1100D listed, along with advice on why you should use--or not use-- each adjustment. Useful tables provide recommended settings for a wide variety of shooting situations, including landscapes, portraits, sports, close-ups, and travel. With this guide on hand, you have all the information you need at your fingertips so you can confidently use your camera on-the-go.', '', '1435460308', '978-1435460300', 'David D. Busch', 2011, 13.99, 9.63, 1, 1),
	(3, 1, 'David Busch\'s Compact Field Guide for the Canon EOS 5D Mark II [Spiral-bound]', 'Are you tired of squinting at the tiny color-coded tables and difficult-to-read text you find on the typical laminated reference card that you keep with you when you\'re in the field or on location? ', 'Are you tired of squinting at the tiny color-coded tables and difficult-to-read text you find on the typical laminated reference card that you keep with you when you\'re in the field or on location? Well throw away your cheat sheets and command cards! DAVID BUSCH\'S COMPACT FIELD GUIDE FOR THE CANON EOS 5D MARK II is your solution. This new, full-color, spiral-bound, reference guide condenses all the must-have information you need while shooting into a portable book you\'ll want to permanently tuck into your camera bag. You\'ll find every settings option for your Canon EOS 5D Mark II listed, along with advice on why you should use--or not use--each adjustment. Useful tables provide recommended settings for a wide variety of shooting situations, including landscapes, portraits, sports, close-ups, and travel. With this guide on hand you have all the information you need at your fingertips so you can confidently use your camera on-the-go.', '', '1435460006', '978-1435460003', 'David D. Busch', 2011, 13.99, 9.99, 1, 2),
	(4, 1, 'Great Photos - Simple Cameras: From Holga to Pinhole: An Alternative Approach to Creative Photography [Paperback] ', 'When we look at everyday life, we realize that it is far from easy. Indeed, we are often confronted with the contrary: complicated instead of simple; demanding instead of effortless.', 'When we look at everyday life, we realize that it is far from easy. Indeed, we are often confronted with the contrary: complicated instead of simple; demanding instead of effortless.', 'Rocky Nook; 1 edition (August 16, 2012)', '1937538028', '978-1937538026', 'Bernd Daub ', 2012, 34.95, 26.56, 1, 4),
	(5, 1, 'How to Create Stunning Digital Photography', 'Stunning Digital Photography is much more than a book; its a hands-on, self-paced photography class with over 14 hours of online training videos and free help from the author and other readers.', 'Stunning Digital Photography is much more than a book; its a hands-on, self-paced photography class with over 14 hours of online training videos and free help from the author and other readers.', 'Peachpit Press; 1 edition (October 6, 2012) ', '0321814339', '978-0321814333', 'Eliot Siegel ', 2012, 54.99, 34.64, 0, 4);



-- Dumping data for table bookstore.saletype: ~5 rows (approximately)
/*!40000 ALTER TABLE `saletype` DISABLE KEYS */;
INSERT INTO `saletype` (`SaleTypeID`, `SaleTypeTitle`, `SaleTypeCssImage`, `SaleTypeCssClass`) VALUES
	(0, 'None', '', ''),
	(1, 'NewBook', 'images/new_icon.gif', 'new_icon'),
	(2, 'Special', 'images/special_icon.gif', 'special_icon'),
	(3, 'Featured', 'images/promo_icon.gif', 'promo_icon'),
	(4, 'Promo', 'images/promo_icon.gif', 'promo_icon');
	
	

INSERT INTO `user` (`UserID`, `UserName`, `Password`, `UserTitle`, `UserTypeID`, `Email`, `Phone`, `FirstName`, `LastName`, `ZipCode`, `Address`, `InsertDateTime`, `ClientIPAddress`, `IsEmailVerified`) VALUES
	(1, 'user1', 'pass', 'User 1', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '', 0),
	(2, 'user2', 'pass', 'User 2', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '', 0),
	(3, 'user3', 'pass', 'User 3', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '', 0),
	(4, 'user4', 'pass', 'User 4', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '', 0),
	(5, 'user5', 'pass', 'User 5', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '', 0);
	
	
