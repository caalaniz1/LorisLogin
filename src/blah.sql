create table `local_profiles` (`id` int unsigned not null auto_increment primary key, `firstName` varchar(20) not null, `lastName` varchar(20) not null, `description` varchar(250) null,   
  `gender` varchar(20) null, `photoUrl` varchar(256) null, `birthDay` int null auto_increment primary key, `birthMonth` int null auto_increment primary key, `birthYear` int   
  null auto_increment primary key, `email` varchar(100) null, `address` varchar(150) null, `country` varchar(20) null, `city` varchar(20) null, `zip` smallint null auto_incr  
  ement primary key, `created_at` timestamp default 0 not null, `updated_at` timestamp default 0 not null, `user_id` int unsigned null) default character set utf8 collate ut  
  f8_unicode_ci
