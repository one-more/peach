create table if not exists `languages` (
  `id`    serial primary key ,
  `alias` varchar (255) not null ,
  `key`   varchar (6) not null
);

create table if not exists `en-EN` (
  `key`   varchar (255) primary key unique ,
  `value` text
);

create table if not exists `ru-RU` (
  `key`   varchar (255) primary key unique ,
  `value` text
);

insert into `languages` (`alias`, `key`) values ('русский', 'ru-RU');

insert into `languages` (`alias`, `key`) values ('english', 'en-EN');

create table if not exists `templates` (
  `id`    serial primary key ,
  `name`  varchar (255) not null ,
  `type`  varchar (255) not null
);

insert into `templates` (`name`, `type`) values ('simple_template', 'site');

insert into `templates` (`name`, `type`) values ('simple_admin_template', 'admin');

create table if not exists `users` (
  `id`          serial primary key ,
  `login`       varchar (255) not null ,
  `password`    varchar (255) not null ,
  `credentials` varchar (255) not null
);

create table if not exists `user_info` (
  `id`        serial primary key ,
  `user`      bigint ,
  `full_name` varchar (255) ,
  `email`     varchar (255) ,
  `phone`     varchar (255) ,
  `icq`       varchar (255) ,
  `skype`     varchar (255) ,
  `site`      varchar (255) ,
  `facebook`  varchar (255) ,
  `twitter`   varchar (255) ,
  `avatar`    varchar (255)
);

create table if not exists `editors` (
  `id`    serial primary key ,
  `name`  varchar (255) not null
);

insert into `editors` (`name`) value ('ckeditor');

insert into `editors` (`name`) value ('tinymce');

create table if not exists `extensions` (
  `id`          serial primary key ,
  `name`        varchar (255) not null ,
  `is_daemon`   boolean
);

create table if not exists `daemons` (
  `id`          serial primary key ,
  `extension`   bigint ,
  `type`        varchar (255) not null
);