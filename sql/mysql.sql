CREATE TABLE `tad_discuss` (
  `DiscussID` mediumint(9) unsigned NOT NULL auto_increment COMMENT '編號',
  `ReDiscussID` mediumint(9) unsigned NOT NULL default 0 COMMENT '回覆編號',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '發布者',
  `publisher` varchar(255) NOT NULL default '' COMMENT '發布者姓名',
  `DiscussTitle` varchar(255) NOT NULL default '' COMMENT '標題',
  `DiscussContent` text NOT NULL COMMENT '內容',
  `DiscussDate` datetime NOT NULL COMMENT '發布時間',
  `BoardID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬討論區',
  `LastTime` datetime NOT NULL COMMENT '最後發表時間',
  `Counter` smallint(6) unsigned NOT NULL default 0 COMMENT '人氣',
  `FromIP` varchar(255) NOT NULL default '' COMMENT 'IP',
  `Good` smallint(6) unsigned NOT NULL default 0 COMMENT '讚',
  `Bad` smallint(6) unsigned NOT NULL default 0 COMMENT '爛',
  `onlyTo` varchar(255) NOT NULL default '' COMMENT '悄悄話',
PRIMARY KEY (`DiscussID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tad_discuss_board` (
  `BoardID` smallint(6) unsigned NOT NULL auto_increment COMMENT '討論版編號',
  `ofBoardID` smallint(6) unsigned NOT NULL default 0 COMMENT '所屬討論版',
  `BoardTitle` varchar(255) NOT NULL default '' COMMENT '討論版名稱',
  `BoardDesc` text NOT NULL COMMENT '討論版說明',
  `BoardManager` varchar(255) NOT NULL default '' COMMENT '板主',
  `BoardSort` smallint(6) unsigned NOT NULL default 0 COMMENT '討論版排序',
  `BoardEnable` enum('1','0') NOT NULL default '1' COMMENT '狀態',
PRIMARY KEY (`BoardID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tad_discuss_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL default '' COMMENT '欄位名稱',
  `col_sn` mediumint(59) unsigned NOT NULL default 0 COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL default 0 COMMENT '排序',
  `kind` enum('img','file') NOT NULL default 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL default '' COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL default 0 COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL default 0 COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL default '' COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL default '' COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL default '' COMMENT '檔案子路徑',
  `upload_date` datetime NOT NULL COMMENT '上傳時間',
  `uid` mediumint(8) unsigned NOT NULL default 0 COMMENT '上傳者',
  `tag` varchar(255) NOT NULL default '' COMMENT '註記',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM COMMENT='tad_discuss 檔案資料表';


CREATE TABLE `tad_discuss_cbox_setup` (
  `setupID` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '設定流水號',
  `setupName` varchar(255) NOT NULL default '' COMMENT '註記',
  `setupRule` varchar(255) NOT NULL default '' COMMENT '偵測字串',
  `BoardID` smallint(6) unsigned NOT NULL default 0 COMMENT '討論版編號',
  `setupSort` smallint(6) unsigned NOT NULL default 0 COMMENT '規則優先權',
PRIMARY KEY (`setupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;