-- Run this script directly in the MySQL server query window it will automatically create the database and all the database objects.


DROP DATABASE IF EXISTS OilComp;
CREATE DATABASE OilComp;


-- Creating OilComp Schema
USE OilComp;

DROP TABLE IF EXISTS CUSTOMERS;
CREATE TABLE CUSTOMERS (
  Cid       	int NOT NULL AUTO_INCREMENT,
  UFname		varchar(15) NOT NULL, 
  ULname    	varchar(15) NOT NULL,
  UPhone		varchar(10),
  UCellPhone	varchar(10),
  Uemail		varchar(30),
  UUsrName		varchar(20),
  UHashPwd		int,
  CStreet		varchar(25),
  CCity		varchar(25),
  CState		varchar(10),
  CZipCode		int,
  CLevel		char(1) NOT NULL, -- G for Gold / S for Silver
  CQBarrels	int,
  CONSTRAINT pk_CustomerId primary key (Cid)
);


DROP TABLE IF EXISTS TRADERS;
CREATE TABLE TRADERS (
  Tid			int NOT NULL AUTO_INCREMENT,
  UFname		varchar(15) NOT NULL, 
  ULname    	varchar(15) NOT NULL,
  UPhone		varchar(10),
  UCellPhone	varchar(10),
  Uemail		varchar(30),
  UUsrName		varchar(20) NOT NULL,
  UHashPwd		int NOT NULL,
  CONSTRAINT pk_TraderId primary key (Tid)
);


DROP TABLE IF EXISTS TRANSACTIONS;
CREATE TABLE TRANSACTIONS (
  Xid			int NOT NULL AUTO_INCREMENT,
  XBuyFlag		char(1) NOT NULL,   -- B for Buy / S for Sell
  XQtbarrels	int NOT NULL,
  XCommValue	decimal(10,2),  
  XCommTypePmt	char(1) NOT NULL,  -- O for Oil / C for Cash
  Xvalue  		decimal(10,2),
  XDate		date,
  Tid			int,
  Cid			int NOT NULL, -- Total particip. on Customers
  CONSTRAINT pk_TransactionId primary key (Xid),
  CONSTRAINT fk_CustomerId foreign key (Cid) references CUSTOMERS(Cid),
  CONSTRAINT fk_TraderId foreign key (Tid) references TRADERS(Tid)
);


DROP TABLE IF EXISTS PAYMENTS;
CREATE TABLE PAYMENTS(
  Tid			int NOT NULL, -- Total particip. on Trader
  Xid			int NOT NULL, -- Total particip. on Transact.
  PAmtPaid		decimal(10,2) NOT NULL, 
  PDate		date,
  CONSTRAINT fk_PmtTraderId foreign key (Tid) references TRADERS(Tid),
  CONSTRAINT fk_PmtTransactionId foreign key (Xid) references TRANSACTIONS (Xid)
);

DROP TABLE IF EXISTS AUDITING;
CREATE TABLE AUDITING (
  Xid			int NOT NULL,
  XBuyFlag		char(1) NOT NULL,   -- B for Buy / S for Sell
  XQTbarrels	int NOT NULL,
  XCommValue	decimal(10,2),  
  XCommTypePmt	char(1) NOT NULL,  
  Xvalue  		decimal(10,2),
  xDate		date,
  Tid			int,
  Cid			int NOT NULL,
  CancDate		date, -- Canceling date
  TidCancel	int  -- Trader who canceled
);

DROP TABLE IF EXISTS PRICE;
CREATE TABLE PRICE(
  PrBarrelPrice	decimal(10,2),
  PrDate		date,
  CONSTRAINT P_PrDate UNIQUE (PrDate)
);





-- Inserting records in CUSTOMERS table 
 
INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Andy', 'Vile', 8985876779, 5122369398, 'Andy.Vile@gmail.com', 'Andy.Vile2017', 51927349, '1967 Jordan', ' Milwaukee', ' WI', 20480, 'G', 175);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Brad', 'Knight', 4617606365, 6111868416, 'Brad.Knight@yahoo.com', 'Brad.Knight', 29528369, '176 Main St.', ' Atlanta', ' GA', 20477, 'B', 344);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Evan', 'Wallis', 6358109847, 9655119958, 'Evan.Wallis@gmail.com', 'Evan.Wallis', 99893875, '134 Pelham', ' Milwaukee', ' WI', 44004, 'B', 425);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Josh', 'Zell', 8061740186, 2683374653, 'Josh.Zell@gmail.com', 'Josh.Zell', 88659556, '266 McGrady', ' Milwaukee', ' WI', 25694, 'B', 287);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jared', 'James', 8506957698, 2658171319, 'Jared.James@yahoo.com', 'Jared.James', 90760096, '123 Peachtree', ' Atlanta', ' GA', 14216, 'B', 181);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Justin', 'Mark', 5382542842, 1334306886, 'Justin.Mark@gmail.com', 'Justin.Mark2017', 67867775, '2342 May', ' Atlanta', ' GA', 11237, 'B', 338);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jon', 'Jones', 6780153336, 1849511938, 'Jon.Jones@gmail.com', 'Jon.Jones', 20211713, '111 Allgood', ' Atlanta', ' GA', 53463, 'B', 180);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('John', 'James', 9469743905, 2876580072, 'John.James@gmail.com', 'John.James', 36820940, '7676 Bloomington', ' Sacramento', ' CA', 40076, 'B', 318);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Alex', 'Freed', 2514075224, 6456100721, 'Alex.Freed@yahoo.com', 'Alex.Freed', 57186624, '4333 Pillsbury', ' Milwaukee', ' WI', 24444, 'B', 75);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Ahmad', 'Jabbar', 1547034080, 9740899058, 'Ahmad.Jabbar@gmail.com', 'Ahmad.Jabbar2017', 33773604, '980 Dallas', ' Houston', ' TX', 12219, 'B', 225);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Joyce', 'English', 7108065544, 3236334210, 'Joyce.English@yahoo.com', 'Joyce.English', 24692875, '5631 Rice', ' Houston', ' TX', 14613, 'G', 250);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Ramesh', 'Narayan', 3048300698, 8278684124, 'Ramesh.Narayan@gmail.com', 'Ramesh.Narayan', 75806238, '971 Fire Oak', ' Humble', ' TX', 15081, 'G', 117);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Alicia', 'Zelaya', 7020058901, 5694713419, 'Alicia.Zelaya@gmail.com', 'Alicia.Zelaya$2017', 22373351, '3321 Castle', ' Spring', ' TX', 29993, 'G', 146);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('John', 'Smith', 6580515958, 4811964981, 'John.Smith@yahoo.com', 'John.Smith', 27458958, '731 Fondren', ' Houston', ' TX', 43786, 'G', 292);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jennifer', 'Wallace', 2101838510, 9377583019, 'Jennifer.Wallace@gmail.com', 'Jennifer.Wallace2017', 36899589, '291 Berry', ' Bellaire', ' TX', 27274, 'B', 473);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Franklin', 'Wong', 1306442005, 6577591727, 'Franklin.Wong@yahoo.com', 'Franklin.Wong', 57952584, '638 Voss', ' Houston', ' TX', 31274, 'B', 310);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('James', 'Borg', 4965314428, 7030767135, 'James.Borg@gmail.com', 'James.Borg', 59637952, '450 Stone', ' Houston', ' TX', 20569, 'B', 432);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Tom', 'Brand', 1885824101, 4135695228, 'Tom.Brand@yahoo.com', 'Tom.Brand', 14951739, '112 Third St', ' Milwaukee', ' WI', 41218, 'G', 467);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jenny', 'Vos', 8920055815, 3156733992, 'Jenny.Vos@gmail.com', 'Jenny.Vos$2017', 94151464, '263 Mayberry', ' Milwaukee', ' WI', 27214, 'B', 237);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Chris', 'Carter', 8568638620, 1688337279, 'Chris.Carter@gmail.com', 'Chris.Carter', 39109581, '565 Jordan', ' Milwaukee', ' WI', 47958, 'G', 167);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Kim', 'Grace', 3326055938, 4195813804, 'Kim.Grace@yahoo.com', 'Kim.Grace', 71055435, '6677 Mills Ave', ' Sacramento', ' CA', 40294, 'B', 192);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jeff', 'Chase', 8665799071, 4654583724, 'Jeff.Chase@gmail.com', 'Jeff.Chase', 78088983, '145 Bradbury', ' Sacramento', ' CA', 16405, 'G', 184);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Bonnie', 'Bays', 1288732976, 2754759696, 'Bonnie.Bays@yahoo.com', 'Bonnie.Bays$2017', 93051457, '111 Hollow', ' Milwaukee', ' WI', 46317, 'G', 296);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Alec', 'Best', 1914261715, 3840494048, 'Alec.Best@gmail.com', 'Alec.Best', 87385672, '233 Solid', ' Milwaukee', ' WI', 15286, 'G', 459);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Sam', 'Snedden', 7573645634, 9655348012, 'Sam.Snedden@gmail.com', 'Sam.Snedden', 15168431, '987 Windy St', ' Milwaukee', ' WI', 37774, 'B', 153);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Nandita', 'Ball', 4424087478, 9514579685, 'Nandita.Ball@yahoo.com', 'Nandita.Ball', 53228377, '222 Howard', ' Sacramento', ' CA', 36060, 'B', 340);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Bob', 'Bender', 2689942399, 3938460283, 'Bob.Bender@gmail.com', 'Bob.Bender', 69405323, '8794 Garfield', ' Chicago', ' IL', 38523, 'B', 176);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jill', 'Jarvis', 5612465391, 2792472408, 'Jill.Jarvis@yahoo.com', 'Jill.Jarvis', 20971640, '6234 Lincoln', ' Chicago', ' IL', 13439, 'G', 239);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Kate', 'King', 5108892451, 3103979011, 'Kate.King@yahoo.com', 'Kate.King', 86315784, '1976 Boone Trace', ' Chicago', ' IL', 53385, 'B', 175);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Lyle', 'Leslie', 5955513758, 5372614544, 'Lyle.Leslie@gmail.com', 'Lyle.Leslie$2017', 31337025, '417 Hancock Ave', ' Chicago', ' IL', 40007, 'G', 420);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Billie', 'King', 6566509691, 9585486353, 'Billie.King@yahoo.com', 'Billie.King', 88800210, '556 Washington', ' Chicago', ' IL', 51789, 'G', 366);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Jon', 'Kramer', 1219185529, 9320271762, 'Jon.Kramer@yahoo.com', 'Jon.Kramer', 68003885, '1988 Windy Creek', ' Seattle', ' WA', 15727, 'B', 378);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Ray', 'King', 2767063239, 6624526949, 'Ray.King@gmail.com', 'Ray.King', 46854277, '213 Delk Road', ' Seattle', ' WA', 11303, 'G', 185);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Gerald', 'Small', 6370169020, 5093747224, 'Gerald.Small@yahoo.com', 'Gerald.Small', 74352052, '122 Ball Street', ' Dallas', ' TX', 45553, 'B', 160);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Arnold', 'Head', 5769685341, 1324724455, 'Arnold.Head@gmail.com', 'Arnold.Head', 72614723, '233 Spring St', ' Dallas', ' TX', 41123, 'G', 118);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Helga', 'Pataki', 9491074566, 8613935740, 'Helga.Pataki@gmail.com', 'Helga.Pataki', 41621514, '101 Holyoke St', ' Dallas', ' TX', 20577, 'G', 467);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Naveen', 'Drew', 6903226717, 4198866978, 'Naveen.Drew@yahoo.com', 'Naveen.Drew', 97152192, '198 Elm St', ' Philadelphia', ' PA', 37055, 'G', 29);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Carl', 'Reedy', 8731603331, 7540883627, 'Carl.Reedy@gmail.com', 'Carl.Reedy', 22407557, '213 Ball St', ' Philadelphia', ' PA', 15125, 'B', 16);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Sammy', 'Hall', 3026659303, 4795974742, 'Sammy.Hall@yahoo.com', 'Sammy.Hall', 35226042, '433 Main Street', ' Miami', ' FL', 26014, 'G', 371);

INSERT INTO CUSTOMERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd, CStreet, CCity, CState, CZipCode, CLevel, CQBarrels) VALUES ('Red', 'Bacher', 7298355245, 3047897145, 'Red.Bacher@gmail.com', 'Red.Bacher', 98611024, '196 Elm Street', ' Miami', ' FL', 11324, 'B', 368);










-- Inserting records in TRADERS table 

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Bob', 'Bender', 7871550061, 1814820189, 'Bob.Bender@gmail.com', 'Bob.Bender2017', 82702636);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Kim', 'Grace', 2219591380, 7589985303, 'Kim.Grace@yahoo.com', 'Kim.Grace', 99161406);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('James', 'Borg', 4865573031, 9958574975, 'James.Borg@gmail.com', 'James.Borg', 70095572);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Alex', 'Freed', 3029583649, 1410394161, 'Alex.Freed@yahoo.com', 'Alex.Freed', 11331896);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Evan', 'Wallis', 9351522408, 2918366404, 'Evan.Wallis@gmail.com', 'Evan.Wallis', 87448136);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Jared', 'James', 3291025273, 7898741695, 'Jared.James@yahoo.com', 'Jared.James', 53594432);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('John', 'James', 3152519680, 7672110741, 'John.James@yahoo.com', 'John.James', 16355625);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Andy', 'Vile', 9508196452, 9242760790, 'Andy.Vile@gmail.com', 'Andy.Vile', 65215529);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Brad', 'Knight', 3651533626, 8551803292, 'Brad.Knight@yahoo.com', 'Brad.Knight', 17782231);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Josh', 'Zell', 2500965962, 2600229822, 'Josh.Zell@gmail.com', 'Josh.Zell', 55574887);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Justin', 'Mark', 3487904540, 8064065360, 'Justin.Mark@yahoo.com', 'Justin.Mark', 40246932);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Jon', 'Jones', 4263736437, 7395248104, 'Jon.Jones@gmail.com', 'Jon.Jones', 95194774);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Ahmad', 'Jabbar', 8062573792, 4458417064, 'Ahmad.Jabbar@gmail.com', 'Ahmad.Jabbar', 80758374);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Joyce', 'English', 7205110173, 7114261272, 'Joyce.English@yahoo.com', 'Joyce.English', 62264789);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Ramesh', 'Narayan', 2436269873, 3688242345, 'Ramesh.Narayan@gmail.com', 'Ramesh.Narayan', 42066510);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('Alicia', 'Zelaya', 5142908794, 4140534868, 'Alicia.Zelaya@gmail.com', 'Alicia.Zelaya', 18533182);

INSERT INTO TRADERS (UFname, ULname, UPhone, UCellPhone, Uemail, UUsrName, UHashPwd) VALUES ('John', 'Smith', 1634008764, 8039926384, 'John.Smith@yahoo.com', 'John.Smith', 65185134);















-- Inserting records in TRANSACTIONS table 

INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 111, 388.59, 'O', 5978.46, '2017-11-15', 17, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 109, 381.59, 'C', 5870.74, '2017-11-15', 8, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 33, 115.52, 'C', 1777.38, '2017-11-15', 11, 24);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 119, 416.6, 'C', 6409.34, '2017-11-15', 15, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 84, 294.07, 'O', 4524.24, '2017-11-15', 7, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 96, 336.08, 'O', 5170.56, '2017-11-16', 13, 24);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 76, 266.06, 'C', 4093.36, '2017-11-16', 16, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 82, 287.07, 'O', 4416.52, '2017-11-16', 5, 29);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 24, 84.02, 'O', 1292.64, '2017-11-16', 11, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 29, 101.52, 'O', 1561.94, '2017-11-16', 7, 26);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 97, 339.58, 'O', 5224.42, '2017-11-16', 13, 12);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 82, 287.07, 'C', 4416.52, '2017-11-16', 5, 25);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 41, 143.53, 'C', 2208.26, '2017-11-16', 17, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 90, 315.08, 'C', 4847.4, '2017-11-16', 12, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 82, 287.07, 'O', 4416.52, '2017-11-16', 9, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 6, 21, 'O', 323.16, '2017-11-17', 15, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 91, 318.58, 'C', 4901.26, '2017-11-17', 2, 18);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 54, 189.04, 'C', 2908.44, '2017-11-17', 13, 31);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 90, 315.08, 'C', 4847.4, '2017-11-17', 15, 40);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 88, 308.07, 'C', 4739.68, '2017-11-17', 12, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 10, 35, 'C', 538.6, '2017-11-17', 11, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 12, 42.01, 'O', 646.32, '2017-11-17', 1, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 117, 409.6, 'O', 6301.62, '2017-11-17', 10, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 77, 269.56, 'O', 4147.22, '2017-11-18', 12, 26);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 66, 231.05, 'O', 3554.76, '2017-11-18', 10, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 56, 196.05, 'O', 3016.16, '2017-11-18', 11, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 76, 266.06, 'O', 4093.36, '2017-11-18', 3, 17);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 44, 154.03, 'O', 2369.84, '2017-11-18', 5, 26);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 33, 115.52, 'C', 1777.38, '2017-11-18', 3, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 85, 297.57, 'C', 4578.1, '2017-11-18', 5, 32);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 120, 420.1, 'C', 6463.2, '2017-11-18', 4, 24);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 76, 266.06, 'C', 4093.36, '2017-11-18', 9, 40);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 41, 143.53, 'O', 2208.26, '2017-11-18', 2, 38);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 114, 399.1, 'C', 6140.04, '2017-11-18', 10, 12);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 77, 269.56, 'O', 4147.22, '2017-11-18', 11, 22);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 30, 105.02, 'C', 1615.8, '2017-11-18', 12, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 10, 35, 'O', 538.6, '2017-11-18', 10, 17);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 57, 199.55, 'C', 3070.02, '2017-11-18', 6, 26);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 104, 364.09, 'O', 5601.44, '2017-11-18', 1, 30);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 79, 276.57, 'C', 4254.94, '2017-11-18', 13, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 84, 294.07, 'O', 4524.24, '2017-11-19', 1, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 7, 24.5, 'C', 377.02, '2017-11-19', 12, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 120, 420.1, 'O', 6463.2, '2017-11-19', 15, 23);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 23, 80.52, 'C', 1238.78, '2017-11-19', 10, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 113, 395.6, 'O', 6086.18, '2017-11-19', 7, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 86, 301.07, 'C', 4631.96, '2017-11-19', 3, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 104, 364.09, 'C', 5601.44, '2017-11-19', 1, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 59, 206.55, 'C', 3177.74, '2017-11-19', 6, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 59, 206.55, 'C', 3177.74, '2017-11-19', 14, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 62, 217.05, 'C', 3339.32, '2017-11-19', 2, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 111, 388.59, 'C', 5978.46, '2017-11-19', 1, 40);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 90, 315.08, 'O', 4847.4, '2017-11-19', 6, 16);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 76, 266.06, 'C', 4093.36, '2017-11-19', 17, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 83, 290.57, 'O', 4470.38, '2017-11-20', 5, 3);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 92, 322.08, 'C', 4955.12, '2017-11-20', 2, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 43, 150.53, 'O', 2315.98, '2017-11-20', 2, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 84, 294.07, 'O', 4524.24, '2017-11-20', 4, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 79, 276.57, 'C', 4254.94, '2017-11-20', 2, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 11, 38.5, 'C', 592.46, '2017-11-20', 12, 25);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 67, 234.56, 'O', 3608.62, '2017-11-20', 11, 33);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 66, 231.05, 'C', 3554.76, '2017-11-20', 13, 8);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 36, 126.03, 'O', 1938.96, '2017-11-20', 3, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 100, 350.09, 'C', 5386, '2017-11-20', 8, 8);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 36, 126.03, 'O', 1938.96, '2017-11-20', 4, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 30, 105.02, 'O', 1615.8, '2017-11-20', 14, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 80, 280.07, 'C', 4308.8, '2017-11-20', 9, 40);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 111, 388.59, 'C', 5978.46, '2017-11-20', 6, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 35, 122.53, 'O', 1885.1, '2017-11-20', 1, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 76, 266.06, 'C', 4093.36, '2017-11-20', 16, 33);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 52, 182.04, 'O', 2800.72, '2017-11-21', 12, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 91, 318.58, 'C', 4901.26, '2017-11-21', 2, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 111, 388.59, 'O', 5978.46, '2017-11-21', 8, 38);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 104, 364.09, 'O', 5601.44, '2017-11-21', 12, 21);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 100, 350.09, 'C', 5386, '2017-11-21', 3, 19);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 16, 56.01, 'C', 861.76, '2017-11-21', 5, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 56, 196.05, 'O', 3016.16, '2017-11-21', 12, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 23, 80.52, 'C', 1238.78, '2017-11-21', 11, 21);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 30, 105.02, 'O', 1615.8, '2017-11-21', 1, 19);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 77, 269.56, 'C', 4147.22, '2017-11-21', 9, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 83, 290.57, 'O', 4470.38, '2017-11-21', 6, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 88, 308.07, 'C', 4739.68, '2017-11-22', 1, 25);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 6, 21, 'C', 323.16, '2017-11-22', 8, 37);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 48, 168.04, 'O', 2585.28, '2017-11-22', 8, 18);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 73, 255.56, 'C', 3931.78, '2017-11-22', 6, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 120, 420.1, 'O', 6463.2, '2017-11-22', 15, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 91, 318.58, 'C', 4901.26, '2017-11-22', 17, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 5, 17.5, 'O', 269.3, '2017-11-22', 11, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 64, 224.05, 'O', 3447.04, '2017-11-22', 11, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 65, 227.55, 'C', 3500.9, '2017-11-22', 1, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 56, 196.05, 'O', 3016.16, '2017-11-22', 12, 30);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 89, 311.58, 'C', 4793.54, '2017-11-23', 13, 32);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 55, 192.54, 'C', 2962.3, '2017-11-23', 9, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 24, 84.02, 'C', 1292.64, '2017-11-23', 5, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 117, 409.6, 'O', 6301.62, '2017-11-23', 5, 29);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 118, 413.1, 'O', 6355.48, '2017-11-23', 16, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 76, 266.06, 'O', 4093.36, '2017-11-23', 17, 3);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 46, 161.04, 'C', 2477.56, '2017-11-23', 3, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 117, 409.6, 'C', 6301.62, '2017-11-23', 2, 24);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 17, 59.51, 'C', 915.62, '2017-11-24', 16, 30);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 86, 301.07, 'O', 4631.96, '2017-11-24', 9, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 99, 346.58, 'O', 5332.14, '2017-11-24', 3, 31);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 17, 59.51, 'C', 915.62, '2017-11-24', 7, 33);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 11, 38.5, 'O', 592.46, '2017-11-24', 6, 31);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 55, 192.54, 'O', 2962.3, '2017-11-24', 7, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 52, 182.04, 'O', 2800.72, '2017-11-24', 9, 27);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 7, 24.5, 'O', 377.02, '2017-11-24', 17, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 101, 353.59, 'C', 5439.86, '2017-11-24', 1, 21);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 96, 336.08, 'C', 5170.56, '2017-11-24', 5, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 75, 262.56, 'C', 4039.5, '2017-11-24', 7, 3);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 85, 297.57, 'O', 4578.1, '2017-11-25', 2, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 55, 192.54, 'O', 2962.3, '2017-11-25', 10, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 106, 371.09, 'C', 5709.16, '2017-11-25', 2, 29);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 74, 259.06, 'C', 3985.64, '2017-11-25', 13, 20);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 41, 143.53, 'C', 2208.26, '2017-11-25', 5, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 52, 182.04, 'C', 2800.72, '2017-11-25', 8, 20);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 12, 42.01, 'C', 646.32, '2017-11-26', 8, 8);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 30, 105.02, 'O', 1615.8, '2017-11-26', 5, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 48, 168.04, 'O', 2585.28, '2017-11-26', 4, 18);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 87, 304.57, 'O', 4685.82, '2017-11-26', 2, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 71, 248.56, 'O', 3824.06, '2017-11-26', 1, 22);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 33, 115.52, 'O', 1777.38, '2017-11-26', 11, 18);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 39, 136.53, 'O', 2100.54, '2017-11-26', 2, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 113, 395.6, 'O', 6086.18, '2017-11-27', 12, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 22, 77.01, 'C', 1184.92, '2017-11-27', 14, 27);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 95, 332.58, 'C', 5116.7, '2017-11-27', 10, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 28, 98.02, 'C', 1508.08, '2017-11-27', 5, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 81, 283.57, 'C', 4362.66, '2017-11-27', 17, 16);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 97, 339.58, 'O', 5224.42, '2017-11-27', 7, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 24, 84.02, 'C', 1292.64, '2017-11-27', 6, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 101, 353.59, 'O', 5439.86, '2017-11-27', 12, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 109, 381.59, 'C', 5870.74, '2017-11-27', 13, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 58, 203.05, 'O', 3123.88, '2017-11-27', 12, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 30, 105.02, 'C', 1615.8, '2017-11-27', 11, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 28, 98.02, 'O', 1508.08, '2017-11-27', 9, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 80, 280.07, 'C', 4308.8, '2017-11-27', 11, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 9, 31.5, 'O', 484.74, '2017-11-27', 8, 21);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 49, 171.54, 'C', 2639.14, '2017-11-27', 9, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 101, 353.59, 'O', 5439.86, '2017-11-28', 4, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 18, 63.01, 'C', 969.48, '2017-11-28', 8, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 46, 161.04, 'O', 2477.56, '2017-11-28', 6, 32);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 53, 185.54, 'C', 2854.58, '2017-11-28', 12, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 25, 87.52, 'C', 1346.5, '2017-11-28', 12, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 69, 241.56, 'C', 3716.34, '2017-11-28', 10, 5);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 95, 332.58, 'C', 5116.7, '2017-11-28', 5, 28);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 55, 192.54, 'C', 2962.3, '2017-11-28', 4, 4);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 50, 175.04, 'C', 2693, '2017-11-28', 11, 18);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 66, 231.05, 'O', 3554.76, '2017-11-28', 5, 33);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 86, 301.07, 'C', 4631.96, '2017-11-28', 9, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 61, 213.55, 'O', 3285.46, '2017-11-28', 13, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 58, 203.05, 'C', 3123.88, '2017-11-28', 14, 1);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 37, 129.53, 'O', 1992.82, '2017-11-28', 11, 19);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 71, 248.56, 'O', 3824.06, '2017-11-28', 4, 30);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 17, 59.51, 'C', 915.62, '2017-11-28', 7, 30);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 53, 185.54, 'C', 2854.58, '2017-11-29', 6, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 75, 262.56, 'O', 4039.5, '2017-11-29', 8, 12);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 39, 136.53, 'C', 2100.54, '2017-11-29', 4, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 20, 70.01, 'O', 1077.2, '2017-11-29', 13, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 29, 101.52, 'C', 1561.94, '2017-11-29', 5, 36);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 30, 105.02, 'O', 1615.8, '2017-11-29', 3, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 66, 231.05, 'O', 3554.76, '2017-11-29', 5, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 5, 17.5, 'C', 269.3, '2017-11-29', 5, 28);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 74, 259.06, 'C', 3985.64, '2017-11-29', 8, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 88, 308.07, 'O', 4739.68, '2017-11-29', 17, 39);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 36, 126.03, 'C', 1938.96, '2017-11-29', 9, 13);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 90, 315.08, 'O', 4847.4, '2017-11-29', 2, 9);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 117, 409.6, 'C', 6301.62, '2017-11-29', 10, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 83, 290.57, 'O', 4470.38, '2017-11-29', 15, 15);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 37, 129.53, 'O', 1992.82, '2017-11-29', 17, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 120, 420.1, 'C', 6463.2, '2017-11-29', 9, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 103, 360.59, 'C', 5547.58, '2017-11-29', 2, 24);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 72, 252.06, 'O', 3877.92, '2017-11-30', 9, 23);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 83, 290.57, 'C', 4470.38, '2017-11-30', 3, 39);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 104, 364.09, 'O', 5601.44, '2017-11-30', 5, 37);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 99, 346.58, 'C', 5332.14, '2017-11-30', 3, 14);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 33, 115.52, 'O', 1777.38, '2017-11-30', 3, 34);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 64, 224.05, 'C', 3447.04, '2017-11-30', 6, 29);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 59, 206.55, 'C', 3177.74, '2017-11-30', 3, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 107, 374.59, 'O', 5763.02, '2017-11-30', 13, 29);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 13, 45.51, 'C', 700.18, '2017-11-30', 15, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 60, 210.05, 'O', 3231.6, '2017-11-30', 8, 20);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 70, 245.06, 'C', 3770.2, '2017-11-30', 6, 39);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 71, 248.56, 'O', 3824.06, '2017-11-30', 7, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 118, 413.1, 'O', 6355.48, '2017-12-01', 17, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 66, 231.05, 'C', 3554.76, '2017-12-01', 10, 35);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 56, 196.05, 'O', 3016.16, '2017-12-01', 10, 11);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 5, 17.5, 'C', 269.3, '2017-12-01', 7, 7);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 36, 126.03, 'C', 1938.96, '2017-12-02', 15, 10);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 88, 308.07, 'C', 4739.68, '2017-12-02', 12, 2);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('B', 69, 241.56, 'O', 3716.34, '2017-12-02', 16, 6);
INSERT INTO TRANSACTIONS (XBuyFlag, XQtbarrels, XCommValue, XCommTypePmt, Xvalue, Xdate, Tid, Cid) VALUES ('S', 88, 308.07, 'O', 4739.68, '2017-12-03', 9, 39);









-- Inserting records in PAYMENT table 

INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (13, 1, 5978.46, '2017-11-25');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (1, 2, 6252.33, '2017-11-25');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (10, 3, 1892.9, '2017-11-25');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (1, 4, 6825.94, '2017-11-26');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (16, 5, 4524.24, '2017-11-26');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (1, 6, 5170.56, '2017-11-26');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (10, 7, 4359.42, '2017-11-26');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (7, 8, 4416.52, '2017-11-27');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (11, 9, 1292.64, '2017-11-27');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (14, 10, 1561.94, '2017-11-27');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (7, 11, 5224.42, '2017-11-28');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (1, 12, 4703.59, '2017-11-28');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (11, 13, 2351.79, '2017-11-28');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (17, 14, 5162.48, '2017-11-29');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (15, 15, 4416.52, '2017-11-29');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (10, 16, 323.16, '2017-11-29');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (3, 17, 5219.84, '2017-11-30');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (9, 18, 3097.48, '2017-11-30');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (5, 19, 5162.48, '2017-11-30');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (13, 20, 5047.75, '2017-11-30');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (17, 21, 573.6, '2017-12-01');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (9, 22, 646.32, '2017-12-01');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (15, 23, 6301.62, '2017-12-01');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (12, 24, 4147.22, '2017-12-01');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (6, 25, 3554.76, '2017-12-01');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (8, 26, 3016.16, '2017-12-02');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (2, 27, 4093.36, '2017-12-02');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (5, 28, 2369.84, '2017-12-03');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (7, 29, 1892.9, '2017-12-03');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (8, 30, 4875.67, '2017-12-03');
INSERT INTO PAYMENTS (Tid, Xid, PAmtPaid, PDate) VALUES (16, 31, 6883.3, '2017-12-03');







-- Inserting records in PRICE table 

INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-01', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-02', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-03', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-04', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-05', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-06', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-07', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-08', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-09', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-10', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-11', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-12', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-13', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-14', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-15', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-16', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-17', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-18', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-19', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-20', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-21', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-22', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-23', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-24', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-25', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-26', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-27', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-28', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-29', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-11-30', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-1', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-2', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-3', 53.86);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-4', 54.2);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-5', 55.12);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-6', 55.27);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-7', 54.39);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-8', 54.88);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-9', 55.67);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-10', 53.48);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-11', 57.2);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-12', 55.4);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-13', 55.43);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-14', 55.34);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-15', 56.21);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-16', 56.24);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-17', 55.97);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-18', 55.88);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-19', 55.73);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-20', 55.44);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-21', 55.67);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-22', 55.99);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-23', 55.76);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-24', 55.89);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-25', 56.12);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-26', 56.17);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-27', 56.29);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-28', 55.98);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-29', 56.21);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-30', 56.04);
INSERT INTO PRICE (PrDate, PrBarrelPrice) VALUES ('2017-12-31', 56.31);












