#用户表
drop table if exists alg_user;
create table alg_user
(
    id int unsigned auto_increment,
    username varchar(64) not null comment '用户名',
    nickname varchar(64) comment '昵称',
    password varchar(64) not null comment '密码',
    email varchar(64) comment '用户邮箱',
    image varchar(255) comment '用户头像',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned,
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    primary key (id),
    unique (username),
    unique (email)
)engine=myisam default charset=utf8 comment='用户表';

INSERT INTO `alg_user` VALUES ('1', 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', '', '1280977330748', '', '0');

#栏目表
drop table if exists alg_category;
create table alg_category
(
    id int unsigned auto_increment,
    name varchar(64) not null comment '栏目名称',
    parent_id int unsigned not null default 0 comment '栏目父级id',
    keywords varchar(64) comment '栏目关键字',
    description varchar(255) comment '栏目描述',
    sort_num smallint(5) unsigned comment '排序',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned,
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    primary key (id)
)engine=myisam default charset=utf8 comment='栏目表';

#标签表
drop table if exists alg_tag;
create table alg_tag
(
    id int unsigned auto_increment,
    name varchar(32) not null comment '标签名称',
    description varchar(255) comment '标签描述',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned,
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    primary key (id)
)engine=myisam default charset=utf8 comment='标签表';

#文章表
drop table if exists alg_article;
create table alg_article
(
    id int unsigned auto_increment,
    title varchar(64) not null comment '文章名称',
    category_id int(10) unsigned comment '栏目id',
    image varchar(255) comment '文章缩略图',
    keywords varchar(64) comment '关键字',
    description varchar(255) comment '描述',
    content text comment '内容',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned,
    hits int(10) unsigned not null default 255 comment '点击次数',
    comment_num int(10) unsigned not null default 0 comment '评价次数',
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    primary key (id)
)engine=myisam default charset=utf8 comment='文章表';

#文章属性表
drop table if exists alg_article_flag;
create table alg_article_flag
(
    article_id int unsigned comment '文章id',
    flag char(8) comment '属性值,如：h=头条,c=推荐'
)engine=myisam default charset=utf8 comment='文章属性表';

#文章标签关联表
drop table if exists alg_article_tag_relation;
create table alg_article_tag_relation
(
    article_id int unsigned comment '文章id',
    tag_id int unsigned comment '标签id',
    primary key (article_id,tag_id)
)engine=myisam default charset=utf8 comment='文章标签关联表';

#评论表
drop table if exists alg_comment;
create table alg_comment
(
    id int unsigned auto_increment,
    article_id int not null comment '文章id',
    content varchar(255) not null comment '评论内容',
    create_time int(10) unsigned not null,
    primary key (id)
)engine=myisam default charset=utf8 comment='评论表';

#回复评论表
drop table if exists alg_reply_comment;
create table alg_reply_comment
(
    id int unsigned auto_increment,
    comment_id int not null comment '评论内容id',
    content varchar(255) not null comment '回复内容',
    create_time int(10) unsigned not null,
    primary key (id)
)engine=myisam default charset=utf8 comment='回复评论表';

#友情链接表
drop table if exists alg_flink;
create table alg_flink
(
    id int unsigned auto_increment,
    name varchar(32) not null comment '名称',
    image varchar(255) comment '网站logo',
    url varchar(255) DEFAULT NULL COMMENT 'url链接',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned,
    sort_num smallint(5) unsigned comment '排序',
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    primary key (id)
)engine=myisam default charset=utf8 comment='友情链接表';

#留言表
drop table if exists alg_message;
create table alg_message
(
    id int unsigned auto_increment,
    title varchar(64) not null comment '标题',
    email varchar(64) comment '用户邮箱',
    content varchar(255) comment '留言内容',
    create_time int(10) unsigned not null,
    primary key (id)
)engine=myisam default charset=utf8 comment='留言表';

#回复留言表
drop table if exists alg_reply_message;
create table alg_reply_message
(
    id int unsigned auto_increment,
    message_id int unsigned not null comment '留言id',
    content varchar(255) comment '回复内容',
    create_time int(10) unsigned not null,
    primary key (id)
)engine=myisam default charset=utf8 comment='回复留言表';

#系统设置表
drop table if exists alg_system;
create table alg_system
(
    title varchar(32) not null comment '网站首页标题',
    image varchar(255) comment '网站logo',
    keywords varchar(64) comment '网站首页关键词',
    description varchar(255) comment '网站首页描述',
    record varchar(32) comment '备案号',
    powerby varchar(255) comment '版权信息'
)engine=myisam default charset=utf8 comment='系统设置表';