##########################
### 用户权限模块表
##########################
#权限表
drop table if exists cms_auth_item;
create table `cms_auth_item`
(
   `name`                 varchar(64) not null,
   `type`                 integer not null,
   `description`          text,
   `bizrule`              text,
   `data`                 text,
   primary key (`name`)
) engine=InnoDB;

#权限关系表(权限之间)
drop table if exists cms_auth_item_child;
create table `cms_auth_item_child`
(
   `parent`               varchar(64) not null,
   `child`                varchar(64) not null,
   primary key (`parent`,`child`),
   foreign key (`parent`) references `cms_auth_item` (`name`) on delete cascade on update cascade,
   foreign key (`child`) references `cms_auth_item` (`name`) on delete cascade on update cascade
) engine=InnoDB;

#权限用户关系表(授权表)
drop table if exists cms_auth_assignment;
create table `cms_auth_assignment`
(
   `itemname`             varchar(64) not null,
   `userid`               varchar(64) not null,
   `bizrule`              text,
   `data`                 text,
   primary key (`itemname`,`userid`),
   foreign key (`itemname`) references `cms_auth_item` (`name`) on delete cascade on update cascade
) engine=InnoDB;

#用户表
# 昵称如果为空，则跟用户名一样
drop table if exists cms_user;
create table cms_user 
(
    id int unsigned auto_increment,
    username varchar(64) not null comment '用户名（账号）',
    password varchar(64) not null,
    nickname varchar(64) not null comment '昵称',
    email varchar(64) not null comment '邮箱（账号）',
    image varchar(255) comment '用户头像',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    status tinyint unsigned not null default 0 comment '2=邮箱未验证，4=邮箱已验证',
    primary key (id),
    unique (username),
    unique (email) 
)engine=InnoDB;

#用户登录信息表
drop table if exists cms_user_login;
create table cms_user_login
(
    id int unsigned auto_increment,
    session_id varchar(32) not null comment '记录session_id',
    user_id int unsigned not null comment '用户id',
    login_time int(10) unsigned not null comment '登录时间',
    logout_time int(10) unsigned comment '登出时间,非正常登出就没有办法记录登出时间',
    ip varchar(32) comment '登录时的ip地址',
    primary key (id)
)engine=InnoDB;

#素材订单表
# 以订单为单位存储素材
drop table if exists tbl_order;
create table tbl_order
(
    id int unsigned auto_increment,
    title varchar(100) not null comment '订单标题',
    number varchar(32) not null default '' comment '订单编号，此为代码自动生成，不可编辑',
    content mediumtext COMMENT '订单内容,存储如商家、品牌、素材等信息',
    room_category tinyint unsigned not null default 0 comment '适合功能空间，0=未使用，1=客厅,2=卧室,3=厨房,4=卫生间,5=书房,6=儿童房,7=餐厅,8=特殊房',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    end_time int(10) unsigned comment '订单完成日期',
    creater_id int unsigned not null,
    updater_id int unsigned ,
    type tinyint(1) unsigned not null default 0 comment '订单类型，0=建模订单,1=渲染订单,2=新空间渲染订单,3=贴图订单',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    status tinyint unsigned not null default 0 comment '订单状态，0=未提交，1=未开始,2=进行中，3=已完成',
    primary key (id)
)engine=InnoDB;

#订单品牌馆关联表
drop table if exists tbl_order_brandhall_relation;
create table tbl_order_brandhall_relation
(
    order_id int unsigned comment '订单ID，订单表外键',
    brandhall_id int unsigned comment '品牌馆ID，品牌馆表外键'
)engine=InnoDB;

#订单空间关联表
# status 订单入驻空间完成状态，即指派空间渲染订单任务给某人后，记录该订单的完成情况
drop table if exists tbl_order_space_relation;
create table tbl_order_space_relation
(
    order_id int unsigned comment '订单ID，订单表外键',
    space_id int unsigned comment '空间ID，空间表外键',
    space_name varchar(64) not null comment '空间名称，格式KT001，WS001',
    status tinyint unsigned not null default 0 comment '订单入驻空间完成状态，0=未提交，1=未开始,2=进行中，3=已完成'
)engine=InnoDB;

#标签表
drop table if exists tbl_label;
create table tbl_label
(
    id int unsigned auto_increment,
    name varchar(64) not null comment '标签名称',
    parent_id int unsigned default 0 comment '父级标签',
    type tinyint unsigned comment '标签类型：2=基础构件，3=家装家具，4=涂装',
    category_id varchar(64) comment '三级系统分类，品类表外键,格式：[1,2,3]',
    sort_num int(11) unsigned DEFAULT 65535 COMMENT '排序值',
    create_time int(10) unsigned not null ,
    update_time int(10) unsigned DEFAULT NULL,
    creater_id int(11) unsigned not null,
    updater_id int(11) unsigned DEFAULT NULL,
    is_del tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认0，1=已删除',
    primary key (id)
)engine=innodb;

#素材表
# 单个素材对应多个模型，但是素材与商品是一对一关系
# 单个素材可以关联多种订单（建模订单、渲染订单、新空间渲染订单）
drop table if exists tbl_info;
create table tbl_info
(
    id int unsigned auto_increment,
    title varchar(100) not null comment '素材标题',
    item varchar(255) comment '素材型号',
    number varchar(32) not null default '' comment '素材编号，此为代码自动生成，不可编辑',
    content mediumtext COMMENT '素材内容,存储如户型，尺寸，贴图等信息',
    image varchar(255) not null comment '素材缩略图jpg',
    category_id int unsigned comment '三级系统分类，品类表外键',
    label_id int unsigned comment '标签ID，存储为最后一级分类',
    brand_id int unsigned comment '品牌/系列id，品牌表外键',
    length varchar(8) not null default 0 comment '长 单位mm',
    width varchar(8) not null default 0 comment '宽 单位mm',
    height varchar(8) not null default 0 comment '高 单位mm，保留字段，暂时没用',	
    texture_id varchar(255) not null default '' comment '贴图id，格式：{"贴图id":"帧值"}如：{"1":2,"2":0,"3":0}',
    type tinyint unsigned not null default 0 comment '0=家具，1=硬装，2=电器，3=配饰',
    mold_condition varchar(255) comment '已上传的模型类型，0=高模(MAX)，1=低模(AWD)，2=阴影模型(AWD)...,格式：{"模型类型":"模型ID","模型类型":"模型ID","模型类型":"模型ID"},如:{"0":"12","1":"345","2":"536"}',
    img_condition varchar(255) comment '已上传的贴图类型，1=透视图,2=顶视图,3=UV贴图,4=法线贴图,5=高光贴图,6=360度,格式：{"贴图类型":"图片数量"},如：{"1":2,"2":0,"3":0}]',
    furniture_pics varchar(255) comment '家具或贴图参考图片目录',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,
    status tinyint unsigned not null default 0 comment '0=待制作，1=进行中,2=已完成',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    brandhall_id int unsigned comment '品牌馆id',
    is_rotation tinyint default 0 comment '是否360旋转，0=否，1=是',
    primary key (id)
)engine=InnoDB;

#订单素材关联表
drop table if exists tbl_order_info_relation;
create table tbl_order_info_relation
(
    order_id int unsigned comment '订单ID，订单表外键',
    info_id int unsigned comment '素材ID，素材表外键'
)engine=InnoDB;

#素材商品关联表
drop table if exists tbl_info_product_relation;
create table tbl_info_product_relation
(
    info_id int unsigned comment '素材ID，素材表外键',
    product_id int unsigned not null comment '商品id',
    brandhall_id int unsigned comment '商品的品牌馆id',
    KEY `product_id` (`product_id`) USING BTREE
)engine=InnoDB;

#素材模型关联表
# 单个素材可以对应多个模型，只是模型的类型不一样，分为高模/简模/阴影模型
drop table if exists tbl_info_mold_relation;
create table tbl_info_mold_relation
(
    info_id int unsigned comment '素材ID，素材表外键',
    mold_id int unsigned comment '模型ID，模型表外键',
    mold_type tinyint unsigned not null default 0 comment '模型类型，0=高模(MAX)，1=低模(AWD)，2=阴影模型(AWD),3=阴影模型(FBX)，4=低模(MAX)，5=低模(FBX)，6=低模(MD2/5)，7=低模(U3D)'
)engine=InnoDB;

#素材功能空间关联表
drop table if exists tbl_info_room_category;
create table tbl_info_room_category
(
    info_id int unsigned comment '素材ID，素材表外键',
    room_category tinyint unsigned not null default 0 comment '适合功能空间，0=未使用，1=客厅,2=卧室,3=厨房,4=卫生间,5=书房,6=儿童房,7=餐厅,8=特殊房'
)engine=InnoDB;

#素材风格关联表
drop table if exists tbl_info_style_relation;
create table tbl_info_style_relation
(
    info_id int unsigned comment '素材ID，素材表外键',
    style_id int unsigned comment '风格ID，风格表外键'
)engine=InnoDB;

#素材颜色关联表
drop table if exists tbl_info_color_relation;
create table tbl_info_color_relation
(
    id int unsigned auto_increment,
    info_id int unsigned comment '素材ID，素材表外键',
    color_name varchar(50) NOT NULL COMMENT '颜色属性名称',
    color_value varchar(50) NOT NULL DEFAULT '' COMMENT '颜色属性值，颜色图片的路径',
    color_sort tinyint(3) DEFAULT '0' COMMENT '颜色的帧',
    primary key (id)
)engine=InnoDB;

#素材材质关联表
drop table if exists tbl_info_material_relation;
create table tbl_info_material_relation
(
    info_id int unsigned not null,
    material_id int unsigned not null
)engine=InnoDB;

#相册表
# 订单的空间参考图，素材的360度图册
drop table if exists cms_album;
create table cms_album
(
    id int unsigned auto_increment,
    obj_id int unsigned not null comment '对象id',
    type tinyint(1) unsigned not null comment '对象类型，1=订单,2=素材',
    image varchar(255) not null comment '参考图',
    summary varchar(255) comment '图片描述',
    sort_num tinyint unsigned not null default 255 comment '图片的排序',
    primary key (id)
)engine=InnoDB;

#任务分配表
# 任务可以以订单或者单个个体（单个个体指的是单个素材或者单个空间）形式分配任务
# 以订单的形式：分配类型->订单的类型->订单id(即obj_id)
# 以单个个体形式：分配类型->订单的类型: 1.建模订单->素材id(即obj_id)->任务类型(即task_type,模型/贴图/QC)
#                                     2.渲染订单->订单id(即obj_id)->空间id(即space_id) 
#                                     3.新空间渲染订单->订单id(即obj_id)
drop table if exists tbl_task_allocation;
create table tbl_task_allocation
(
    id int unsigned auto_increment,
    obj_id int unsigned not null comment '对象id,如果类型是订单则为订单id，素材则为素材id',
    space_id int unsigned not null default 0 comment '渲染订单下的空间id',
    order_type tinyint(1) unsigned not null comment '订单类型，0=建模订单,1=渲染订单,2=新空间渲染订单,3=贴图订单',
    allocation_type tinyint(1) unsigned not null comment '分配类型，0=订单,1=单个,单个指的是单个空间或者单个素材',
    task_type tinyint(1) unsigned not null default 0  comment '任务类型，0=未使用,1=模型,2=贴图,3=QC',
    sender int unsigned not null comment '用户id(发布任务者)',
    receiver int unsigned not null comment '用户id(接受任务者)',
    create_time int(10) unsigned not null comment '发布任务时间',
    status tinyint(1) unsigned not null default 0 comment '任务是否完成,0=未完成,1=已完成',
    is_check tinyint(1) unsigned not null default 0 comment '是否审核,0=未审核,1=已审核,2=审核不通过',
    primary key (id)
)engine=InnoDB;

#任务审核不通过表
drop table if exists tbl_task_error;
create table tbl_task_error
(
    id int auto_increment,
    task_id int not null comment '任务分配表id',
    summary text not null comment '描述错误信息，图文信息',
    create_time datetime not null,
    update_time datetime ,
    creater_id int not null,
    updater_id int ,
    status tinyint(4) not null default 0 comment '状态（0,1保留不用）,2=历史的错误信息（重新进入审核或者审核通过的标志为历史的）',
    primary key (id)
)engine=innodb;

#模型表
drop table if exists tbl_mold;
create table tbl_mold
(
    id int unsigned auto_increment,
    name varchar(255) not null comment '模型名称',
    item varchar(255) comment '模型型号',
    floorplan varchar(255) not null default '' comment '模型顶视图png',
    image varchar(255) not null default '' comment '模型缩略图jpg',
    mold varchar(255) not null default '' comment '模型文件',
    type tinyint unsigned not null comment '0=家具，1=硬装，2=电器，3=配饰',
    length varchar(8) not null default 0 comment '长 单位mm',
    width varchar(8) not null default 0 comment '宽 单位mm',
    height varchar(8) not null default 0 comment '高 单位mm，保留字段，暂时没用',	
    summary varchar(255) comment '模型说明',
    product_id int(10) unsigned DEFAULT '0' COMMENT '商品id，未绑定的为0',
    category_id int unsigned comment '三级系统分类，品类表外键',
    label_id int unsigned comment '标签ID，存储为最后一级分类',
    brand_id int unsigned comment '品牌/系列id，品牌表外键',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,	
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    status tinyint unsigned not null default 0 comment '保留字段',
    brandhall_id int unsigned comment '品牌馆id',
    mold_type tinyint unsigned not null default 0 comment '模型类型，0=高模(MAX)，1=低模(AWD)，2=阴影模型(AWD),3=阴影模型(FBX)，4=低模(MAX)，5=低模(FBX)，6=低模(MD2/5)，7=低模(U3D)',
    texture_id varchar(255) not null default '' comment '贴图id，格式：{"贴图id":"帧值"}如：{"1":2,"2":0,"3":0}',
    maker varchar(32) comment '制作者',
    is_old tinyint(1) unsigned not null default 0 comment '是否为老数据，0=新数据,1=老数据，新数据即由订单素材生产出来的模型',
    primary key (id) 
)engine=innodb;

#贴图表
drop table if exists tbl_texture;
create table tbl_texture
(
    id int unsigned auto_increment,
    name varchar(255) not null default '' comment '贴图名称',
    color_name varchar(60) COMMENT '颜色名称，如：白色',
    color_value varchar(255) COMMENT '颜色路径，如：/common/images/color/Bai.png',
    floorplan varchar(255) comment '模型顶视图png',
    image varchar(255) comment '模型缩略图jpg',
    uv_map varchar(255) comment 'UV贴图(小图)，格式：["\/upload\/dsds.jpg","\/upload\/dsds.jpg","\/upload\/dsds.jpg"]',
    m_uv_map varchar(255) comment 'UV贴图(大小图)，格式：["\/upload\/dsds.jpg","\/upload\/dsds.jpg","\/upload\/dsds.jpg"]',
    normal_map varchar(255) comment '法线贴图(小图)，格式：["\/upload\/dsds.jpg","\/upload\/dsds.jpg","\/upload\/dsds.jpg"]',
    m_normal_map varchar(255) comment '法线贴图(大图)，格式：["\/upload\/dsds.jpg","\/upload\/dsds.jpg","\/upload\/dsds.jpg"]',
    specular_map varchar(255) comment '保留字段，高光贴图，模型对应的贴图才会有高光贴图',
    alpha tinyint unsigned not null default 0 comment '1=有透明通道',
    length int unsigned not null default 0 comment '尺寸，长',
    width int unsigned not null default 0 comment '尺寸，宽',
    height int unsigned not null default 0 comment '尺寸，高',
    maker varchar(32) comment '制作者',
    primary key (id)
)engine=innodb;

#模型材质关联表
drop table if exists tbl_mold_material_relation;
create table tbl_mold_material_relation
(
    mold_id int unsigned not null,
    material_id int unsigned not null
)engine=InnoDB;

#模型风格关联关系表
drop table if exists tbl_mold_style_relation;
create table tbl_mold_style_relation
(
    mold_id int unsigned not null ,
    style_id int unsigned not null 
)engine=InnoDB;

#空间表
drop table if exists tbl_space;
create table tbl_space
(
    id int unsigned auto_increment,
    name varchar(64) not null comment '空间名称，格式KT001，WS001',
    out_name varchar(64) not null comment '空间对外名称,默认跟name一样',
    image varchar(255) not null comment '空间封面图，即flash加载时候的初始图片，选择其中一个角度的空模图作为空间封面图',
    pics text not null comment '空间空模图，{"angle1":"/upload/","angle2":"/upload/"}',
    showpics text not null comment '空间效果展示图，{"angle1":"/upload/","angle2":"/upload/"}',
    floorplan text comment '空间平面布局图，{"angle1":"/upload/","angle2":"/upload/"}',	
    room_category tinyint unsigned not null comment '1=客厅,2=卧室,3=厨房,4=卫生间,5=书房,6=儿童房,7=餐厅,8=特殊房',
    summary varchar(255) comment '空间描述',
    # 空间尺寸
    length varchar(8) not null default 0 comment '长 单位mm，真实长度',
    width varchar(8) not null default 0 comment '宽 单位mm，真实宽度',
    height varchar(8) not null default 0 comment '高 单位mm，真实高度，保留字段',	
    max_length varchar(8) not null default 0 comment '长 单位mm，max中的长度，生产系统使用',
    max_width varchar(8) not null default 0 comment '宽 单位mm，max中的宽度，生产系统使用',
    max_height varchar(8) not null default 0 comment '高 单位mm，max中的高度，生产系统使用，保留字段',

    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,	
    # 统计数值
    hot_num int unsigned not null default 0 comment '热度值，数值越大越热门',		
    showroom_num int unsigned not null default 0 comment '样板间数量',
    plan_num int unsigned not null default 0 comment '方案数量',
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    is_del tinyint unsigned not null default 0 comment '0=正常,1=删除',
    status tinyint unsigned not null default 0 comment '0=未规划层级(用于标识生产系统规划区域)，1=已规划层级(用于标识生产系统规划区域)',
    is_common tinyint(1) unsigned not null default 0 comment '0=非公共空间，1=公共空间',
    is_360 tinyint(1) unsigned not null default 0 comment '是否为360空间，0=否，1=是',
    primary key (id) 
)engine=InnoDB;

#空间元素关联表
drop table if exists tbl_space_element_relation;
create table tbl_space_element_relation
(
    space_id int unsigned not null,
    element_id int unsigned not null,
    room_category tinyint unsigned not null,
    KEY `element_id` (`element_id`) USING BTREE
)engine=InnoDB;

#样板间表
# 递归关联，为父级新增元素的时候，子级也新增元素
# 为子级增加元素，父级不增加元素
# 只能设置自己的多视角样板间
# 品牌馆样板间删除的时候递归删除,即brandhall_id不为null的样板间，删除的时候，要删除子集样板间
drop table if exists tbl_showroom;
create table tbl_showroom
(
    id int unsigned auto_increment,
    parent_id int unsigned default 0 comment '父级样板间,记录授权样板间',
    brandhall_id int unsigned comment '品牌馆id',
    name varchar(128) not null comment '样板间名称',
    image varchar(255) comment '样板间封面',
    coverpic_id int unsigned comment '封面图片id,从方案中选择设置',
    angle varchar(32) not null comment '视角',
    angles varchar(255) comment '多视角,{"angle1":srid1, "angle2":srid2, "angle3":srid3}',
    space_id int unsigned not null comment '空间id',
    room_category tinyint unsigned not null comment '1=客厅,2=卧室,3=厨房,4=卫生间,5=书房,6=儿童房,7餐厅，8=特殊房',
    # brand_id varchar(255) not null default '' comment '样板间中的商品品牌，格式：1,2,3',
    # category_id varchar(255) not null default '' comment '样板间中的元素品类，格式：2,3,4',
    create_time int(10) unsigned not null,
    update_time int(10) unsigned ,
    creater_id int unsigned not null,
    updater_id int unsigned ,   
    # 状态
    is_show tinyint unsigned not null default 1 comment '是否显示该样板间，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），3=仅对自己显示，默认1',
    is_recommend tinyint(1) unsigned not null default 0 comment '是否为推荐样板间，0=否，1=是',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    # 统计数值
    element_num int unsigned not null default 0 comment '关联元素数',
    max_element_num int unsigned not null default 0 comment '能使用的元素上限',
    plan_num int unsigned not null default 0 comment '方案数',
    recommend_plan_num int unsigned not null default 0 comment '推荐方案数',
    max_recommend_plan_num int unsigned not null default 0 comment '推荐方案数上限',
    primary key (id) 
)engine=InnoDB;

#样板间元素关联表
drop table if exists tbl_showroom_element_relation;
create table tbl_showroom_element_relation
(
    showroom_id int unsigned not null comment '样板间id',
    element_id int unsigned not null comment '元素id',	
    sort_num tinyint unsigned not null default 255 comment '绑定元素的排序，数值越小越靠前',
    recommend_sort_num tinyint unsigned default 255 not null comment '推荐元素的排序，数值越小越靠前',
    is_show tinyint unsigned not null default 1 comment '是否在样板间中显示元素，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    status tinyint unsigned not null default 0 comment '0=绑定元素，1=推荐元素(推荐元素的前提就是绑定，所以推荐元素也是绑定元素)',
    KEY `element_id` (`element_id`) USING BTREE
)engine=InnoDB;

#样板间品牌关联表
drop table if exists tbl_showroom_brand_relation;
create table tbl_showroom_brand_relation
(
    showroom_id int unsigned not null ,
    brand_id int unsigned not null comment '末级品牌id',
    brandhall_id int unsigned not null default 0 comment '品牌馆id',
    is_show tinyint unsigned not null default 1 comment '是否在样板间中显示商品，0=全部隐藏，1=全部显示，默认1',
    is_mine tinyint unsigned not null default 1 comment '是否为我的品牌，1=是，0=否' 
)engine=InnoDB;

#样板间品类关联表
drop table if exists tbl_showroom_category_relation;
create table tbl_showroom_category_relation
(
    showroom_id int unsigned not null ,
    category_id int unsigned not null comment '系统第三级分类id',
    brandhall_id int unsigned not null default 0 comment '品牌馆id'
)engine=InnoDB;

##########################
### 元素模块表
##########################
#元素表
# pics说明： dapei->flash搭配图,hot->热区图
# 当前就只有两张图片
drop table if exists tbl_element;
create table tbl_element
(
    id int unsigned auto_increment,
    name varchar(128) not null comment '元素名称',
    image varchar(255) not null comment '元素封面图，直接调用模型缩略图（不单独上传）',
    pics text not null comment '渲染生成的元素图片,json格式存储{"angle1":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"},"angle2":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"}}',
    pics_night text null comment '渲染生成的夜景元素图片,json格式存储{"angle1":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"},"angle2":{"dapei_pic":"图片相对路径","hot_pic":"/upload/"}}',
    type tinyint unsigned not null comment '0=家具，1=硬装，2=电器，3=配饰，4=热点元素',
    summary varchar(255) comment '元素说明',
    category_id int unsigned comment '三级系统分类，品类表外键',
    label_id int unsigned comment '标签ID，存储为最后一级分类',
    brand_id int unsigned comment '品牌/系列id，品牌表外键',
    rank int unsigned comment '综合排名，数字越小排在越前面，默认情况下等于搭配次数，管理员可手动设置',
    mold_id int unsigned comment '模型id，每个元素都有一个对应的模型（前期手动上传的元素没有模型）',
    create_time int(10) unsigned not null comment '一周内创建的元素在flash中显示为新增元素',
    update_time int(10) unsigned ,
    creater_id int unsigned not null comment '创建者',
    updater_id int unsigned ,
    # 状态字段
    is_show tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
    is_del tinyint(1) unsigned not null default 0 comment '0=正常,1=删除',
    is_default tinyint(1) unsigned not null default 0 comment '保留字段，暂时没有要求实现手动设置默认显示元素，是否默认加载到flash中，0=否，1=是',
    is_recommend tinyint(1) unsigned not null default 0 comment '是否为推荐元素，0=否，1=是',
    # 排序
    sort_num tinyint unsigned not null default 255 comment '显示顺序，数值越小越靠前',
    # 统计字段
    dapei_num int unsigned default 0 comment 'flash中的搭配次数，保存成方案才算搭配一次',	

    brandhall_id int unsigned comment '品牌馆id，定制生产此元素的品牌馆id',
    primary key (id) 
)engine=InnoDB;

#元素层级表
# 元素在某空间下某视角下的层级
drop table if exists tbl_element_layer;
create table tbl_element_layer
(
    element_id int unsigned not null ,
    space_id int unsigned not null ,
    angle char(2) not null comment '视角',
    layer char(2) not null comment '元素层级,01',
    name varchar(32) comment '层级名称',
    KEY `element_id` (`element_id`) USING BTREE
)engine=InnoDB;

#元素适用空间
drop table if exists tbl_element_room_category;
create table tbl_element_room_category
(
    element_id int unsigned not null ,
    room_category tinyint not null comment '1=客厅,2=卧室,3=厨房,4=卫生间,5=书房,6=儿童房,7=餐厅,8=特殊房'
)engine=InnoDB;

#记录定制元素和授权获得商品的父id信息
drop table if exists tbl_element_product_relation;
create table tbl_element_product_relation
(
    brandhall_id int unsigned not null comment '品牌馆id',
    element_id int unsigned not null comment '子级定制的元素id',
    product_id int unsigned not null comment '授权商品的父商品id'
)engine=InnoDB;

#元素风格关联关系表
drop table if exists tbl_element_style_relation;
create table tbl_element_style_relation
(
    element_id int unsigned not null ,
    style_id int unsigned not null
)engine=InnoDB;

#元素材质关联表
drop table if exists tbl_element_material_relation;
create table tbl_element_material_relation
(
    element_id int unsigned not null,
    material_id int unsigned not null
)engine=InnoDB;

##########################
### 商品模块表
##########################
drop table if exists sp_product;
CREATE TABLE `sp_product` (
  `product_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品自增编号',
  `parent_id` int(11) unsigned comment '父级id，授权/取消授权用',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类ID',
  `cat_alias_id` int(11) unsigned not null default 0 comment '保留字段，商品虚拟分类id',
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  # `style_id` varchar(255) NOT NULL default '' COMMENT '风格(1,2,3,4)',
  `brandhall_id` int unsigned NOT NULL DEFAULT '0' COMMENT '品牌馆ID',
  # `room_categories` varchar(50) NOT NULL COMMENT '适用空间，卧室、客厅等，英文逗号隔开的数字串(1,2,3)',
  `product_name` varchar(120) NOT NULL COMMENT '商品名称',
  `product_sn` varchar(60) NOT NULL COMMENT '商品货号',
  `product_number` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '库存数量',
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '库存警告数量',
  `product_weight` decimal(10,2) unsigned NOT NULL COMMENT '商品重量，以KG为单位',
  `product_desc` text COMMENT '详细描述',
  `product_summary` varchar(255) comment '简单描述', 
  # `product_thumb` varchar(255) NOT NULL COMMENT '缩略图链接地址',
  `product_img` varchar(255) NOT NULL COMMENT '商品原始图片链接地址',
  `is_on_sale` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '能否销售(1上架、0下架)',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价格，仅供参考',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商铺售价',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `promote_discount` decimal(5,2) unsigned not null default '0.00' comment '折扣',
  `promote_start_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销开始日期',
  `promote_end_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销结束日期',
  `goods_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类型ID，关联表attr_type',
  `keywords` varchar(255) NOT NULL default '' COMMENT '商品关键词',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `like_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `sales_total` int(11) unsigned default 0 comment '商品总销量',
  `sales_month` int(11) unsigned default 0 comment '商品月销量',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排列顺序',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除, 1=删除，0 = 正常',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新品，1=新品',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热销 1=热销',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否特价 1=特价',
  # 审核
  `is_check` tinyint unsigned not null default 0 comment '0=未审核，1=审核通过，2=审核不通过 邮件提醒',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付货到付款,1=支持',
  `element_num` int not null default 0 comment '元素数，大于0即为可搭配商品',	
  # `in_pics` tinyint(1) not null default 0 comment '是否使用了图片空间,图片空间要实现按存储量收费',
  `is_recommend` tinyint(1) unsigned not null default 0 comment '是否为推荐商品，0=否，1=是',
  # `is_combination` tinyint unsigned default 0 comment '0=未被组合，1=被组合，2=组合商品',
  # `is_customizable` tinyint unsigned default 0 comment '是否可定制，0=否，1=是，2=可局部定制',
  `is_detachable` tinyint(1) unsigned default 0 comment '是否可拆洗，0=否，1=是',
  `is_show` tinyint unsigned not null default 1 comment '是否显示，0=全部隐藏，1=全部显示，2=对未授权用户隐藏（只对授权用户显示），默认1',
  `is_buy` tinyint(1) unsigned not null default 1 comment '是否可以购买，0=否，1=是',
  `mold_id` varchar(255) comment '模型id,格式：1,2,3',
  `standard_number` int unsigned default 1 comment '标配数量',
  `is_mine` tinyint unsigned not null default 1 comment '1=自己的商品，0=别人公开的',
  `unit` varchar(32) default '个' comment '单位',
  `texture_id` varchar(255) not null default '' comment '贴图id，格式：{"贴图id":"帧值"}如：{"1":2,"2":0,"3":0}',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';

#商品模型关联表
# 单个商品可以绑定多个模型，只是模型的类型不一样，分为高模/简模/阴影模型,即单个商品只能绑定单种类型的模型
drop table if exists tbl_product_mold_relation;
create table tbl_product_mold_relation
(
    product_id int unsigned comment '商品ID，商品表外键',
    mold_id int unsigned comment '模型ID，模型表外键',
    mold_type tinyint unsigned not null default 0 comment '0=高模，1=简模，2=阴影模型',
    KEY `product_id` (`product_id`) USING BTREE
)engine=InnoDB;

#商品元素关联表
# num就是设置一个硬装元素上绑定对应商品的个数,如一堵墙用到了3桶漆
drop table if exists tbl_product_element_relation;
create table tbl_product_element_relation
(
    product_id int unsigned not null,
    element_id int unsigned not null,
    num int unsigned default 1 comment '元素上商品的数量'
)engine=InnoDB;

#商品材质关联表
drop table if exists tbl_product_material_relation;
create table tbl_product_material_relation
(
    product_id int unsigned not null,
    material_id int unsigned not null
)engine=InnoDB;

#商品风格关联表
drop table if exists tbl_product_style_relation;
create table tbl_product_style_relation
(
    product_id int unsigned not null ,
    style_id int unsigned not null 
)engine=InnoDB;

#商品自定义品类关联表
# 这张表只记录商品和商家自定义品类的关联关系
drop table if exists tbl_product_category_relation;
create table tbl_product_category_relation
(
   product_id int unsigned not null comment '产品id',
   category_id int unsigned not null comment '分类id'	
)engine=InnoDB;

#站内信
drop table if exists cms_sitemail;
CREATE TABLE `cms_sitemail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) unsigned NOT NULL COMMENT '发信人id',
  `dialog_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '默认为0，回复时为回复消息对应的主键id',
  `cmt_id` int(11) unsigned NOT NULL COMMENT '社区id',
  `cmt_name` varchar(50) NOT NULL COMMENT '社区名称',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `addtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `counts` smallint(5) unsigned DEFAULT NULL COMMENT '阅读次数',
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认0，1=已删除',
  `type` tinyint unsigned not null default 0 comment '0=普通，1=超管群发，2=。。。',
  `main_id` int unsigned comment '主贴id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息发送表';

drop table if exists cms_sitemail_member;
CREATE TABLE `cms_sitemail_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) unsigned NOT NULL COMMENT '接收用户ID',
  `mail_id` int(11) unsigned NOT NULL COMMENT '站内信ID',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态  0：未读；1：已读',
  `read_time` int(10) unsigned DEFAULT NULL COMMENT '阅读时间',
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认0，1=已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息接收表';
