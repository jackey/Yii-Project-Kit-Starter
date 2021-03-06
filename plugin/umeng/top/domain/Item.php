<?php

/**
 * 商品信息
 * @author auto create
 */
class Item
{
	
	/** 
	 * 售后服务ID,该字段仅在taobao.item.get接口中返回
	 **/
	public $afterSaleId;
	
	/** 
	 * 应用AppKey
	 **/
	public $appkey;
	
	/** 
	 * 商品上传后的状态。onsale出售中，instock库中
	 **/
	public $approveStatus;
	
	/** 
	 * 商品的积分返点比例。如:5,表示:返点比例0.5%
	 **/
	public $auctionPoint;
	
	/** 
	 * 代充商品类型。在代充商品的类目下，不传表示不标记商品类型（交易搜索中就不能通过标记搜到相关的交易了）。可选类型： no_mark(不做类型标记) time_card(点卡软件代充) fee_card(话费软件代充)
	 **/
	public $autoFill;
	
	/** 
	 * 自动重发,true/false
	 **/
	public $autoRepost;
	
	/** 
	 * 商品级别的条形码
	 **/
	public $barcode;
	
	/** 
	 * 应用回调地址
	 **/
	public $callbackUrl;
	
	/** 
	 * 基础色数据
	 **/
	public $changeProp;
	
	/** 
	 * 天猫超市扩展字段，天猫超市专用。
	 **/
	public $chaoshiExtendsInfo;
	
	/** 
	 * 商品所属的叶子类目 id
	 **/
	public $cid;
	
	/** 
	 * 货到付款运费模板ID
	 **/
	public $codPostageId;
	
	/** 
	 * cpv_memo
	 **/
	public $cpvMemo;
	
	/** 
	 * Item的发布时间，目前仅供taobao.item.add和taobao.item.get可用
	 **/
	public $created;
	
	/** 
	 * 定制工具Id
	 **/
	public $customMadeTypeId;
	
	/** 
	 * 下架时间（格式：yyyy-MM-dd HH:mm:ss）
	 **/
	public $delistTime;
	
	/** 
	 * 发货时间信息
	 **/
	public $deliveryTime;
	
	/** 
	 * 商品描述, 字数要大于5个字符，小于25000个字符
	 **/
	public $desc;
	
	/** 
	 * 宝贝描述规范化模块锚点信息
	 **/
	public $descModuleInfo;
	
	/** 
	 * 商品描述模块化，模块列表，由List转化成jsonArray存入，后端逻辑验证通过，拼装成模块内容+锚点导航后存入desc中。数据结构具体参见Item_Desc_Module
	 **/
	public $descModules;
	
	/** 
	 * 商品url
	 **/
	public $detailUrl;
	
	/** 
	 * ems费用,格式：5.00；单位：元；精确到：分
	 **/
	public $emsFee;
	
	/** 
	 * 快递费用,格式：5.00；单位：元；精确到：分
	 **/
	public $expressFee;
	
	/** 
	 * 宝贝特征值，只有在Top支持的特征值才能保存到宝贝上
	 **/
	public $features;
	
	/** 
	 * 食品安全信息，包括：生产许可证编号、产品标准号、厂名、厂址等
	 **/
	public $foodSecurity;
	
	/** 
	 * 运费承担方式,seller（卖家承担），buyer(买家承担）
	 **/
	public $freightPayer;
	
	/** 
	 * 全球购商品采购地信息（地区/国家），代表全球购商品的产地信息。
	 **/
	public $globalStockCountry;
	
	/** 
	 * 全球购商品发货地，发货地现在有两种类型：“国内”和“海外及港澳台”，参数值为1时代表“国内”，值为2时代表“海外及港澳台”
	 **/
	public $globalStockDeliveryPlace;
	
	/** 
	 * 全球购商品卖家包税承诺，当值为true时，代表卖家承诺包税。
	 **/
	public $globalStockTaxFreePromise;
	
	/** 
	 * 全球购商品采购地信息（库存类型），有两种库存类型：现货和代购;参数值为1时代表现货，值为2时代表代购
	 **/
	public $globalStockType;
	
	/** 
	 * 支持会员打折,true/false
	 **/
	public $hasDiscount;
	
	/** 
	 * 是否有发票,true/false
	 **/
	public $hasInvoice;
	
	/** 
	 * 橱窗推荐,true/false
	 **/
	public $hasShowcase;
	
	/** 
	 * 是否有保修,true/false
	 **/
	public $hasWarranty;
	
	/** 
	 * 商品iid
	 **/
	public $iid;
	
	/** 
	 * 加价幅度。如果为0，代表系统代理幅度。在竞拍中，为了超越上一个出价，会员需要在当前出价上增加金额，这个金额就是加价幅度。卖家在发布宝贝的时候可以自定义加价幅度，也可以让系统自动代理加价。系统自动代理加价的加价幅度随着当前出价金额的增加而增加，我们建议会员使用系统自动代理加价，并请买家在出价前看清楚加价幅度的具体金额。另外需要注意是，此功能只适用于拍卖的商品。以下是系统自动代理加价幅度表：当前价（加价幅度 ）1-40（ 1 ）、41-100（ 2 ）、101-200（5 ）、201-500 （10）、501-1001（15）、001-2000（25）、2001-5000（50）、5001-10000（100）10001以上         200
	 **/
	public $increment;
	
	/** 
	 * 用户内店宝贝装修模板id
	 **/
	public $innerShopAuctionTemplateId;
	
	/** 
	 * 针对当前商品的自定义属性值
	 **/
	public $inputCustomCpv;
	
	/** 
	 * 用户自行输入的类目属性ID串。结构："pid1,pid2,pid3"，如："20000"（表示品牌） 注：通常一个类目下用户可输入的关键属性不超过1个。
	 **/
	public $inputPids;
	
	/** 
	 * 用户自行输入的子属性名和属性值，结构:"父属性值;一级子属性名;一级子属性值;二级子属性名;自定义输入值,....",如：“耐克;耐克系列;科比系列;科比系列;2K5”，input_str需要与input_pids一一对应，注：通常一个类目下用户可输入的关键属性不超过1个。所有属性别名加起来不能超过 3999字节。
	 **/
	public $inputStr;
	
	/** 
	 * 是否是3D淘宝的商品
	 **/
	public $is3D;
	
	/** 
	 * 是否为达尔文挂接成功了的商品
	 **/
	public $isCspu;
	
	/** 
	 * 是否在外部网店显示
	 **/
	public $isEx;
	
	/** 
	 * 非分销商品：0，代销：1，经销：2
	 **/
	public $isFenxiao;
	
	/** 
	 * 是否24小时闪电发货
	 **/
	public $isLightningConsignment;
	
	/** 
	 * 是否是线下商品。1：线上商品（默认值）；2：线上或线下商品；3：线下商品。
	 **/
	public $isOffline;
	
	/** 
	 * 商品是否为先行赔付taobao.items.search和taobao.items.vip.search专用
	 **/
	public $isPrepay;
	
	/** 
	 * 是否在淘宝显示
	 **/
	public $isTaobao;
	
	/** 
	 * 是否定时上架商品
	 **/
	public $isTiming;
	
	/** 
	 * 虚拟商品的状态字段
	 **/
	public $isVirtual;
	
	/** 
	 * 标示商品是否为新品。值含义：true-是，false-否。
	 **/
	public $isXinpin;
	
	/** 
	 * 商品图片列表(包括主图)。fields中只设置item_img可以返回ItemImg结构体中所有字段，如果设置为item_img.id、item_img.url、item_img.position等形式就只会返回相应的字段
	 **/
	public $itemImgs;
	
	/** 
	 * 表示商品的体积，用于按体积计费的运费模板。该值的单位为立方米（m3）。该值支持两种格式的设置：格式1：bulk:3,单位为立方米(m3),表示直接设置为商品的体积。格式2：weight:10;breadth:10;height:10，单位为米（m）
	 **/
	public $itemSize;
	
	/** 
	 * 商品的重量，用于按重量计费的运费模板。注意：单位为kg
	 **/
	public $itemWeight;
	
	/** 
	 * 上架时间（格式：yyyy-MM-dd HH:mm:ss）
	 **/
	public $listTime;
	
	/** 
	 * 本地生活电子交易凭证业务，目前此字段只涉及到的信息为有效期:如果有效期为起止日期类型，此值为2012-08-06,2012-08-16如果有效期为【购买成功日 至】类型则格式为2012-08-16如果有效期为天数类型则格式为3
	 **/
	public $localityLife;
	
	/** 
	 * 商品所在地
	 **/
	public $location;
	
	/** 
	 * 商品修改时间（格式：yyyy-MM-dd HH:mm:ss）
	 **/
	public $modified;
	
	/** 
	 * 宝贝主图视频的数据信息，包括：视频ID，视频缩略图URL，视频时长，视频状态等信息。
	 **/
	public $mpicVideo;
	
	/** 
	 * 家装分阶段价格数据结构
	 **/
	public $msPayment;
	
	/** 
	 * 是否为新消保法中的7天无理由退货
	 **/
	public $newprepay;
	
	/** 
	 * 卖家昵称
	 **/
	public $nick;
	
	/** 
	 * 商品数量
	 **/
	public $num;
	
	/** 
	 * 商品数字id
	 **/
	public $numIid;
	
	/** 
	 * 是否绑定o2o
	 **/
	public $o2oBindService;
	
	/** 
	 * 是否淘1站商品
	 **/
	public $oneStation;
	
	/** 
	 * open_iid
	 **/
	public $openIid;
	
	/** 
	 * 商家外部编码(可与商家外部系统对接)。需要授权才能获取。
	 **/
	public $outerId;
	
	/** 
	 * 用户外店装修模板id
	 **/
	public $outerShopAuctionTemplateId;
	
	/** 
	 * 用于保存拍卖有关的信息
	 **/
	public $paimaiInfo;
	
	/** 
	 * 周期销售库存
	 **/
	public $periodSoldQuantity;
	
	/** 
	 * 商品主图片地址
	 **/
	public $picUrl;
	
	/** 
	 * 平邮费用,格式：5.00；单位：元；精确到：分
	 **/
	public $postFee;
	
	/** 
	 * 宝贝所属的运费模板ID，如果没有返回则说明没有使用运费模板
	 **/
	public $postageId;
	
	/** 
	 * 商品价格，格式：5.00；单位：元；精确到：分
	 **/
	public $price;
	
	/** 
	 * 宝贝所属产品的id(可能为空). 该字段可以通过taobao.products.search 得到
	 **/
	public $productId;
	
	/** 
	 * 消保类型，多个类型以,分割。可取以下值：2：假一赔三；4：7天无理由退换货；taobao.items.search和taobao.items.vip.search专用
	 **/
	public $promotedService;
	
	/** 
	 * 商品属性图片列表。fields中只设置prop_img可以返回PropImg结构体中所有字段，如果设置为prop_img.id、prop_img.url、prop_img.properties、prop_img.position等形式就只会返回相应的字段
	 **/
	public $propImgs;
	
	/** 
	 * 属性值别名,比如颜色的自定义名称
	 **/
	public $propertyAlias;
	
	/** 
	 * 商品属性 格式：pid:vid;pid:vid
	 **/
	public $props;
	
	/** 
	 * 商品属性名称。标识着props内容里面的pid和vid所对应的名称。格式为：pid1:vid1:pid_name1:vid_name1;pid2:vid2:pid_name2:vid_name2……(<strong>注：</strong><font color="red">属性名称中的冒号":"被转换为："#cln#";  分号";"被转换为："#scln#"</font>)
	 **/
	public $propsName;
	
	/** 
	 * 商品资质的信息，用URLEncoder做过转换，使用时，需要URLDecoder转换回来，默认字符集为：UTF-8
	 **/
	public $qualification;
	
	/** 
	 * 商品所属卖家的信用等级数，1表示1心，2表示2心……，只有调用商品搜索:taobao.items.get和taobao.items.search的时候才能返回
	 **/
	public $score;
	
	/** 
	 * 秒杀商品类型。打上秒杀标记的商品，用户只能下架并不能再上架，其他任何编辑或删除操作都不能进行。如果用户想取消秒杀标记，需要联系小二进行操作。如果秒杀结束需要自由编辑请联系活动负责人（小二）去掉秒杀标记。可选类型web_only(只能通过web网络秒杀)wap_only(只能通过wap网络秒杀)web_and_wap(既能通过web秒杀也能通过wap秒杀)
	 **/
	public $secondKill;
	
	/** 
	 * 达尔文数据挂接，第二步保存结果
	 **/
	public $secondResult;
	
	/** 
	 * 商品卖点信息，天猫商家使用字段，最长150个字符。
	 **/
	public $sellPoint;
	
	/** 
	 * 是否承诺退换货服务!
	 **/
	public $sellPromise;
	
	/** 
	 * 商品所属的店铺内卖家自定义类目列表
	 **/
	public $sellerCids;
	
	/** 
	 * 店铺类型，B：天猫店铺，C：淘宝店铺
	 **/
	public $shopType;
	
	/** 
	 * <a href="http://open.taobao.com/dev/index.php/Sku">Sku</a>列表。fields中只设置sku可以返回Sku结构体中所有字段，如果设置为sku.sku_id、sku.properties、sku.quantity等形式就只会返回相应的字段
	 **/
	public $skus;
	
	/** 
	 * 商品销量
	 **/
	public $soldQuantity;
	
	/** 
	 * 商品新旧程度(全新:new，闲置:unused，二手：second)
	 **/
	public $stuffStatus;
	
	/** 
	 * 商品是否支持拍下减库存:1支持;2取消支持(付款减库存);0(默认)不更改 集市卖家默认拍下减库存; 商城卖家默认付款减库存
	 **/
	public $subStock;
	
	/** 
	 * 商品的子标题，给商品增加卖点等描述
	 **/
	public $subTitle;
	
	/** 
	 * 页面模板id
	 **/
	public $templateId;
	
	/** 
	 * 商品标题,不能超过60字节
	 **/
	public $title;
	
	/** 
	 * 商品类型(fixed:一口价;auction:拍卖)注：取消团购
	 **/
	public $type;
	
	/** 
	 * 有效期,7或者14（默认是7天）
	 **/
	public $validThru;
	
	/** 
	 * 该字段废弃，请勿使用。
	 **/
	public $videoId;
	
	/** 
	 * 商品视频列表(目前只支持单个视频关联)。fields中只设置video可以返回Video结构体中所有字段，如果设置为video.id、video.video_id、video.url等形式就只会返回相应的字段
	 **/
	public $videos;
	
	/** 
	 * 商品是否违规，违规：true , 不违规：false
	 **/
	public $violation;
	
	/** 
	 * 对应搜索商品列表页的最近成交量,只有调用商品搜索:taobao.items.get和taobao.items.search的时候才能返回
	 **/
	public $volume;
	
	/** 
	 * 不带html标签的desc文本信息，该字段只在taobao.item.get接口中返回
	 **/
	public $wapDesc;
	
	/** 
	 * 适合wap应用的商品详情url ，该字段只在taobao.item.get接口中返回
	 **/
	public $wapDetailUrl;
	
	/** 
	 * 无线的宝贝描述
	 **/
	public $wirelessDesc;
	
	/** 
	 * 预扣库存，即付款减库存的商品现在有多少处于未付款状态的订单
	 **/
	public $withHoldQuantity;
	
	/** 
	 * 商品所属的商家的旺旺在线状况，taobao.items.search和taobao.items.vip.search专用
	 **/
	public $wwStatus;	
}
?>