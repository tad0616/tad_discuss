CREATE TABLE `tad_discuss` (
  `DiscussID` mediumint(9) unsigned NOT NULL auto_increment COMMENT '編號',
  `ReDiscussID` mediumint(9) unsigned NOT NULL COMMENT '回覆編號',
  `uid` smallint(6) unsigned NOT NULL COMMENT '發布者',
  `DiscussTitle` varchar(255) NOT NULL COMMENT '標題',
  `DiscussContent` text NOT NULL COMMENT '內容',
  `DiscussDate` datetime NOT NULL COMMENT '發布時間',
  `BoardID` smallint(6) unsigned NOT NULL COMMENT '所屬討論區',
  `LastTime` datetime NOT NULL COMMENT '最後發表時間',
  `Counter` smallint(6) unsigned NOT NULL COMMENT '人氣',
  `FromIP` varchar(255) NOT NULL COMMENT 'IP',
  `Good` smallint(6) unsigned NOT NULL COMMENT '讚',
  `Bad` smallint(6) unsigned NOT NULL COMMENT '爛',
PRIMARY KEY (`DiscussID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_discuss_board` (
  `BoardID` smallint(6) unsigned NOT NULL auto_increment COMMENT '討論版編號',
  `BoardTitle` varchar(255) NOT NULL COMMENT '討論版名稱',
  `BoardDesc` text NOT NULL COMMENT '討論版說明',
  `BoardManager` varchar(255) NOT NULL COMMENT '板主',
  `BoardSort` smallint(6) unsigned NOT NULL COMMENT '討論版排序',
  `BoardEnable` enum('1','0') NOT NULL COMMENT '狀態',
PRIMARY KEY (`BoardID`)
) ENGINE=MyISAM;

CREATE TABLE `tad_discuss_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL COMMENT '欄位名稱',
  `col_sn` smallint(5) unsigned NOT NULL COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL COMMENT '排序',
  `kind` enum('img' , 'file') NOT NULL COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL COMMENT '下載人次',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM COMMENT='檔案資料表';

