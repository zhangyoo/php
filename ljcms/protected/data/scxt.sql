#区域表，用于标示元素放置的位置
# 该表仅供生产系统使用
drop table if exists tbl_area;
create table tbl_area
(
    id int unsigned auto_increment,
    x varchar(8) not null,
    y varchar(8) not null,
    length varchar(8) not null default 0 comment '长 单位mm',
    width varchar(8) not null default 0 comment '宽 单位mm',
    height varchar(8) not null default 0 comment '高 单位mm，保留字段，暂时没用',	
    distance varchar(8) not null default 0 comment '离地高度',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,	
    primary key (id)
)engine=innodb;

#层级表
drop table if exists tbl_node;
create table tbl_node
(
    id int unsigned auto_increment,
    space_id int unsigned not null comment '空间id',
    area_id int unsigned not null default 0 comment '区域id,硬装层级没有对应的区域',
    layer text not null comment '某视角下区域的层级({"angle1":"01"})',
    distance varchar(8) not null default 0 comment '离地高度',
    name varchar(64) not null comment '层级的名称',	
    type tinyint unsigned not null comment '0=家具，1=硬装，2=电器，3=配饰，4=热点元素',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,	
    primary key (id) 
)engine=innodb;

#已提交渲染表（可以简单的理解为已渲染表）
# 该表仅供生产系统使用
drop table if exists tbl_render;
create table tbl_render
(
    id int auto_increment,
    mold_id int not null comment '模型',
    space_id int not null comment '空间',
    status tinyint(4) not null default 0 comment '状态（0,1保留不用），保留字段',
    primary key (id)
)engine=innodb;

#############################################################################################
######		自动生成临时元素表
#############################################################################################
#临时元素表
# 临时存储的元素需要审核之后才存到真实元素表tbl_element中
drop table if exists tbl_element_temp;
create table tbl_element_temp
(
    id int unsigned auto_increment,
    name varchar(128) not null comment '元素名称',
    image varchar(255) not null comment '元素封面图，直接调用模型缩略图（不单独上传）',
    pics text not null comment '渲染生成的元素图片,json格式存储{"angle1":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"},"angle2":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"}}',
    pics_night text null comment '渲染生成的夜景元素图片,json格式存储{"angle1":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"},"angle2":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"}}',
    layer text not null comment '层级,格式：{"空间id":{"视角":"层级"}},如：{"1":{"angle1":"01","angle2":"02"},"2":{"angle1":"01","angle2":"02"}}',
    type tinyint unsigned not null comment '0=家具，1=硬装，2=电器，3=配饰，4=热点元素',
    summary varchar(255) comment '元素说明',
    category_id int unsigned comment '三级系统分类，品类表外键',
    label_id int unsigned comment '标签ID，存储为最后一级分类',
    brand_id int unsigned comment '品牌/系列id，品牌表外键',
    style_id varchar(255) default '' COMMENT '风格(1,2,3,4)',
    material_id varchar(255) default '' COMMENT '材质(1,2,3,4)',
    rank int unsigned comment '综合排名，数字越小排在越前面，默认情况下等于搭配次数，管理员可手动设置',
    mold_id int unsigned not null default 0 comment '模型id，每个元素都有一个对应的模型（前期手动上传的元素没有模型）',
    create_time int(10) unsigned not null comment '一周内创建的元素在flash中显示为新增元素',
    update_time int(10) unsigned ,
    creater_id int unsigned comment '创建者',
    updater_id int unsigned ,
    # 状态字段
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    is_default tinyint(1) unsigned not null default 0 comment '保留字段，暂时没有要求实现手动设置默认显示元素，是否默认加载到flash中，0=否，1=是',
    is_recommend tinyint(1) unsigned not null default 0 comment '是否为推荐元素，0=否，1=是',
    status tinyint(4) not null default 0 comment '状态（0,1保留不用），2=未审核，3=审核不通过，4=审核通过（等待写入元素表），5=已写入元素表',
    # 排序
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',
    # 统计字段
    dapei_num int unsigned default 0 comment 'flash中的搭配次数，保存成方案才算搭配一次',	
    brandhall_id int unsigned comment '品牌馆id，定制生产此元素的品牌馆id',
    primary key (id)
)engine=innodb;

#元素审核不通过的时候，对应的数据的num值要清零
#dapei+hot
drop table if exists tbl_element_prepare;
create table tbl_element_prepare
(
    id int auto_increment,
    name varchar(256) comment '元素名称',
    num tinyint(2) comment '图片数量，目前是num=2的时候开始生成元素，后期可能会+1',
    status tinyint(4) not null default 0 comment '状态（0,1保留不用），2=未生成元素（未写入元素临时表），4=已生成元素（已写入元素临时表）',
    primary key (id)
)engine=innodb;

#元素审核不通过表
# 考虑是不是舍弃掉
#记录每一次的不通过信息（使用kindeditor输入错误信息）
drop table if exists tbl_element_error;
create table tbl_element_error
(
    id int auto_increment,
    element_id int not null comment '临时元素表id',
    summary text not null comment '描述错误信息，图文信息',
    create_time datetime not null,
    update_time datetime ,
    creater_id int not null,
    updater_id int ,
    status tinyint(4) not null default 0 comment '状态（0,1保留不用）,2=历史的错误信息（重新进入审核或者审核通过的标志为历史的）',
    primary key (id)
)engine=innodb;
