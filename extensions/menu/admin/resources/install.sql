create table if not exists `url` (
	`id` 	serial primary key,
	`url`	varchar(255) not null
);

create table if not exists `layout_url` (
	`id`		serial primary key,
	`layout`	bigint not null,
	`url`		bigint not null
); 

create table if not exists `layout` (
	`id`			serial primary key,
	`name`			varchar(255) not null,
	`extension`		varchar(255) not null,
	`class`			varchar(255) not null,
	`controller`	varchar(255) not null,
	`position`		varchar(255) not null	
);

create table if not exists `layout_params` (
	`id` 		serial primary key,
	`layout`	bigint not null,
	`params`	text
);

create table if not exists `menus` (
	`id`		serial primary key,
	`name`		varchar (255) not null
);

create table if not exists `menu_items` (
	`id`		serial primary key,
	`name`		varchar(255) not null,
	`url`		varchar(255) not null,
	`menu`		bigint not null,
	`parent`	bigint	
);
