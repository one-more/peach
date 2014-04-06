CREATE TABLE IF NOT EXISTS `ru_articles` (
	`id`  		serial primary key,
	`title` 	varchar(255) NOT NULL,
	`text`		text NOT NULL,
	`category`	bigint(32) NOT NULL,
	`date`		TIMESTAMP,
	`author`	bigint(32),
	`published`	boolean	
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `ru_article_categories` (
	`id` 	serial primary key,
	`name`	varchar(255) NOT NULL
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `ru_article_tags` (
	`id`	serial primary key,
	`name`	varchar(255) NOT NULL
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `ru_articles_tags` (
	`id` 		serial primary key,
	`article`	bigint(32) NOT NULL,
	`tag`		bigint(32) NOT NULL
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `en_articles` (
	`id`  		serial primary key,
	`title` 	varchar(255) NOT NULL,
	`text`		text NOT NULL,
	`category`	bigint(32) NOT NULL,
	`date`		TIMESTAMP,
	`author`	bigint(32),
	`published`	boolean	
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `en_article_categories` (
	`id` 	serial primary key,
	`name`	varchar(255) NOT NULL
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `en_article_tags` (
	`id`	serial primary key,
	`name`	varchar(255) NOT NULL
)engine=InnoDB;

CREATE TABLE IF NOT EXISTS `en_articles_tags` (
	`id` 		serial primary key,
	`article`	bigint(32) NOT NULL,
	`tag`		bigint(32) NOT NULL
)engine=InnoDB;  
