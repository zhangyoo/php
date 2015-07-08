#数据表
drop table if exists tbl_table;
create table tbl_table
(
    id int unsigned auto_increment,
    tbl_name varchar(64) not null comment '表全名，包括表前缀',
    db_name varchar(64) not null comment '所属数据库名',
    primary key (id)
)engine=myisam;

#风格表
drop table if exists tbl_style;
create table tbl_style
(
    id int unsigned auto_increment,
    name varchar(32) not null,
    letter varchar(32) comment '首字母简写',
    summary varchar(128) comment '风格描述',
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',	
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,   
    primary key (id),
    unique (name)
)engine=myisam;

#品牌表
drop table if exists tbl_brand;
create table tbl_brand
(
    id int unsigned auto_increment,
    parent_id int unsigned comment '父品牌',
    brandhall_id int unsigned comment '品牌所有者，品牌馆id',
    logo varchar(255) comment '品牌logo',
    name varchar(64) not null,
    letter varchar(64) comment '首字母简写',
    summary varchar(255) comment '品牌介绍',
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',	
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    # 审核
    is_check tinyint unsigned not null default 0 comment '0=未审核，1=审核通过，2=审核不通过 邮件提醒',
    primary key (id),
    unique (name) 
)engine=myisam;

#品类表
# 由于自定义品类存在重名的概率比较高，所以name没有unique约束,系统品类还是不能重名，需要在程序里判断
drop table if exists tbl_category;
create table tbl_category
(
    id int unsigned auto_increment,
    parent_id int unsigned comment '父类',
    brandhall_id int unsigned not null default 0 comment '品牌馆id',
    name varchar(64) not null,
    letter varchar(64) comment '首字母简写',
    room_category varchar(32) comment '所属功能空间:1,2,3',
    # level tinyint(4) not null default 1 comment '1=一级分类，2=二级分类，3=三级分类',
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',	
    summary varchar(255) comment '品类说明',	
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,	
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    status tinyint unsigned not null default 0 comment '保留字段',
    primary key (id)
)engine=myisam;

#材质表
drop table if exists tbl_material;
create table tbl_material
(
    id int unsigned auto_increment,
    parent_id int unsigned comment '父材质',
    name varchar(32) not null,
    letter varchar(32) comment '首字母简写',
    summary varchar(128) comment '材质描述',
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',	
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,   
    primary key (id),
    unique (name) 
)engine=myisam;

#品牌馆
# 审核通过之后初始化菜单信息，并发邮件和短信提醒商家
drop table if exists tbl_brandhall;
create table tbl_brandhall
(
    id int unsigned auto_increment,
    name varchar(64) not null comment '品牌馆名称',
    banner varchar(64)  comment '品牌标语',
    summary varchar(255) comment '品牌馆简介',
    domain varchar (128) not null comment '品牌馆的二级域名',
    brand_id varchar(255) not null default '' comment '品牌馆经营品牌，格式：2,3,4',
    category_id varchar(255) not null default '' comment '品牌馆经营品类,格式：1,2,3',
    business_license varchar(255) not null comment '营业执照',
    logo_square varchar(255) comment '正方形logo',
    logo_rectangle varchar(255) comment '长方形logo',
    square_link varchar(255) comment '链接地址',
    rectangle_link varchar(255) comment '链接地址',
    version tinyint(4) unsigned not null default 0 comment '品牌馆版本,0=免费版本，1=品牌厂商,2=经销商,3=家居卖场,4=装企,5=设计师,6=房产公司,7=物业公司',
    # version varchar(64) not null comment '品牌馆版本，格式：1,2,3',
    vip_level tinyint unsigned not null default 0 comment '会员等级，一年升一个等级',
    region_level tinyint unsigned not null default 3 comment '区域等级，1=省，2=市，3=区',
    default_style varchar(32) not null default 'brandhall_default' comment '默认样式，配置文件里加一个css_style参数',
    default_region text comment '百度地图默认显示色块区域,格式：（省|市,市\n省|市,市\n市,市）',
    property_id text comment '收藏的楼盘,["楼盘id"]',
    background_id int comment '品牌馆背景',
    # region_id text not null comment '省市区{"省id":{"市id":[区id]}}，格式：{"1":{"2":[3,4,5],"7":[8,9]}}',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null comment '创建者，即品牌馆的拥有者(品牌馆需要过户功能，由管理员创建的，可以通过过户功能将品牌馆转给客户)',
    updater_id int unsigned comment '父品牌馆能够修改子品牌馆',
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    # is_cert tinyint(1) unsigned not null default 0 comment '0=未认证，1=已认证',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    # is_init tinyint(1) unsigned not null default 0 comment '品牌馆是否已初始化，0=未初始化，1=已初始化',
    # status tinyint unsigned not null default 0 comment '2=待审核，4=审核通过，6=审核不通过',
    deadline int(10) comment '授权过期时间，取消授权即是将时间设置为当前时间，延长授权即是延长过期时间',
    max_collect_apartment_num int unsigned not null default 0 comment '可收藏户型数',
    collect_apartment_num int unsigned not null default 0 comment '已收藏户型数',
    max_nav tinyint unsigned not null default 6 comment '一级菜单的上限',
    # 审核
    is_check tinyint unsigned not null default 0 comment '0=未审核，1=审核通过，2=审核不通过 邮件提醒',
    primary key (id),
    unique (name),
    unique (domain)
)engine=InnoDB;

