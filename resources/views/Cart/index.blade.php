<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <title>我的购物车</title>

    <link rel="stylesheet" type="text/css" href="/static/css/webbase.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/pages-cart.css" />
    <script src="/static/js/jquery.js"></script>
    <script>
        $(document).ready(function () {
            // 全部|全选
            $(document).on("click", ".shop_all", function () {
                var _this = $(this);
                // 商品数量
                shop_count = $(".chosed").children("span");
                $(":checkbox").prop("checked", _this.prop('checked'));
                shop_count.text($(".goods_child").length);
                money = 0;
                if(_this.prop("checked") == true) {
                    $(".sum").each(function () {
                        money = parseFloat(parseFloat(($(this).text())) + parseFloat(money))
                    })
                    // console.log(money);
                    $(".summoney").text(money);
                }else{
                    shop_count.text(0);
                    // console.log(money);
                    $(".summoney").text(money);
                }
            })
            $(document).on("click", ".shops", function () {
                var _this = $(this);
                var _checked = _this.prop('checked');
                _this.parents(".cart-shop").next(".cart-body").find(":checkbox").prop("checked", _this.prop('checked'));
            })
            $(document).on("click", ".goods_child", function () {
                var _this = $(this);
                if (_this.prop("checked") == false) {
                    _this.parents(".cart-body").prev().children(".shops").prop("checked", false)
                    // 获取当前购买的数量
                    var count = _this.parent().siblings(".yui3-u-1-8").children("span[class='sum']").text();
                    // console.log(count)
                    var money = parseFloat($(".summoney").text());
                    $(".summoney").text((money * 10000 - count * 10000) / 10000)
                } else {
                    _this.parents(".cart-body").prev().children(".shops").prop("checked", true)
                    // 获取当前购买的数量
                    var count = _this.parent().siblings(".yui3-u-1-8").children("span[class='sum']").text();
                    // console.log(count)
                    var money = parseFloat($(".summoney").text());
                    $(".summoney").text((money * 10000 + count * 10000) / 10000)
                }
            })
            $(document).on("click", ".increment", function () {
                var _this = $(this);
                _itxt = parseFloat(_this.siblings(".itxt").val());
                var _price = parseFloat(_this.parents(".yui3-u-1-8").prev("").find(".price").text());
                var num = parseFloat(_this.parents(".yui3-u-1-8").next("").find(".sum").text())
                // console.log(_price)
                var money = parseFloat($(".summoney").text());
                if (_this.text() == '+') {
                    _this.siblings("input").val(_itxt + 1);
                    _this.parents(".yui3-u-1-8").next("").find(".sum").text((num * 10000 + _price * 10000) / 10000)
                    console.log(_this.parents(".goods-list").find(".goods_child"))
                    if(_this.parents(".goods-list").find(".goods_child").prop("checked") == true){
                        $(".summoney").text((money * 10000 + _price * 10000) / 10000)
                    }
                } else {
                    if (_itxt >= 2) {
                        _this.siblings("input").val(_itxt - 1);
                        _this.parents(".yui3-u-1-8").next("").find(".sum").text((num * 10000 - _price * 10000) / 10000)
                        if(_this.parents(".goods-list").find(".goods_child").prop("checked") == true){
                            $(".summoney").text((money * 10000 - _price * 10000) / 10000)
                        }
                    }
                }
                // _this.siblings(".itxt").val(_itxt+1);
            })
            $(document).on("click", ".increment_mins", function () {
                var _this = $(this);
                // if(this)
            })
            // 结算
            // $(document).on("click", ".sum-btn", function () {
            //     shop_count = $(".chosed").children("span").text();
            //     if(shop_count <= 0){
            //         alert('请选择商品');
            //         return false;
            //     }
            //     obj = [[], [], [], [], [], [], [], []];
            //     //获取用户需要购买的商品
            //     $(".cart-item-list").each(function (key) {
            //         var _this = $(this);
            //         //得到商品的id
            //         var goods_id = _this.find(".goods_child");
            //         // console.log(goods_id)
            //         goods_id.each(function (res) {
            //             if ($(this).prop("checked") == true) {
            //                 obj[key]['goods_id'] = $(this).parents(".cart-item-list").find(".goods_child").val();
            //                 obj[key]['goods_count'] = $(this).parents(".cart-item-list").find(".itxt").val();
            //             }
            //         })
            //     })
            //     var jsonStr = JSON.stringify(obj)
            //     console.log(   consolejsonStr);
            //     return false;
            //     $.ajax({
            //         url:'order/pay',
            //         data:obj,
            //         type:'post',
            //     }).done(function (res) {
            //         alert(res)
            //     })
            // })
        })
    </script>
</head>

<body>
<!--head-->
<div class="top">
    <div class="py-container">
        <div class="shortcut">
            <ul class="fl">
                <li class="f-item">品优购欢迎您！</li>
                <li class="f-item">请登录　<span><a href="#">免费注册</a></span></li>
            </ul>
            <ul class="fr">
                <li class="f-item">我的订单</li>
                <li class="f-item space"></li>
                <li class="f-item">我的品优购</li>
                <li class="f-item space"></li>
                <li class="f-item">品优购会员</li>
                <li class="f-item space"></li>
                <li class="f-item">企业采购</li>
                <li class="f-item space"></li>
                <li class="f-item">关注品优购</li>
                <li class="f-item space"></li>
                <li class="f-item">客户服务</li>
                <li class="f-item space"></li>
                <li class="f-item">网站导航</li>
            </ul>
        </div>
    </div>
</div>
<div class="cart py-container">
    <!--logoArea-->
    <div class="logoArea">
        <div class="fl logo"><span class="title">购物车</span></div>
        <div class="fr search">
            <form class="sui-form form-inline">
                <div class="input-append">
                    <input type="text" type="text" class="input-error input-xxlarge" placeholder="品优购自营" />
                    <button class="sui-btn btn-xlarge btn-danger" type="button">搜索</button>
                </div>
            </form>
        </div>
    </div>
    <!--All goods-->
    <div class="allgoods">
        <h4>全部商品<span></span></h4>
        <div class="cart-main">
            <div class="yui3-g cart-th">
                <div class="yui3-u-1-4"><input type="checkbox" name="" class="shop_all" id="" value="" /> 全部</div>
                <div class="yui3-u-1-4">商品</div>
                <div class="yui3-u-1-8">单价（元）</div>
                <div class="yui3-u-1-8">数量</div>
                <div class="yui3-u-1-8">小计（元）</div>
                <div class="yui3-u-1-8">操作</div>
            </div>
            @foreach($CartInfo as $k=>$v)
                <div class="cart-item-list">
                    <div class="cart-shop">
                        <input type="checkbox" class="shops" name="" id="" value="" />
                        <span class="shopname self">{{$v['cate_name']}}</span>
                    </div>
                    <div class="cart-body">
                        @foreach($v['child'] as $key=>$value)
                            <div class="cart-list">
                            <ul class="goods-list yui3-g">
                                <li class="yui3-u-1-24">
                                    <input type="checkbox" class="goods_child" name="" id="" value="{{$value['goods_id']}}" />
                                </li>
                                <li class="yui3-u-11-24">
                                    <div class="good-item">
                                        <div class="item-img"><img src="/static/img/goods.png" /></div>
                                        <div class="item-msg">{{$value['goods_name']}}</div>
                                    </div>
                                </li>

                                <li class="yui3-u-1-8"><span class="price">{{$value['shop_price']}}</span></li>
                                <li class="yui3-u-1-8">
                                    <a href="javascript:void(0)" class="increment mins">-</a>
                                    <input autocomplete="off" type="text" readonly value="1" minnum="1" class="itxt" />
                                    <a href="javascript:void(0)" class="increment plus">+</a>
                                </li>
                                <li class="yui3-u-1-8"><span class="sum">{{$value['shop_price']}}</span></li>
                                <li class="yui3-u-1-8">
                                    <a href="#none">删除</a><br />
                                    <a href="#none">移到我的关注</a>
                                </li>
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="cart-tool">
            <div class="select-all">
                <input type="checkbox" name="" class="shop_all" id="" value="" />
                <span>全选</span>
            </div>
            <div class="option">
                <a href="#none">删除选中的商品</a>
                <a href="#none">移到我的关注</a>
                <a href="#none">清除下柜商品</a>
            </div>
            <div class="toolbar">
                <div class="chosed">已选择<span>0</span>件商品</div>
                <div class="sumprice">
                    <span><em>总价（不含运费） ：</em>¥<i class="summoney">0</i></span>
                    <span><em>已节省：</em><i>-¥20.00</i></span>
                </div>
                <div class="sumbtn">
                    <style>
                        .sum-btn {
                            display: block;
                            position: relative;
                            width: 96px;
                            height: 52px;
                            line-height: 52px;
                            color: #fff;
                            text-align: center;
                            font-size: 18px;
                            font-family: "Microsoft YaHei";
                            background: #e54346;
                            overflow: hidden;
                        }
                    </style>
                    <a class="sum-btn" href="cart/getOrderInfo" target="_blank">结算</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="deled">
            <span>已删除商品，您可以重新购买或加关注：</span>
            <div class="cart-list del">
                <ul class="goods-list yui3-g">
                    <li class="yui3-u-1-2">
                        <div class="good-item">
                            <div class="item-msg">Apple Macbook Air 13.3英寸笔记本电脑 银色（Corei5）处理器/8GB内存</div>
                        </div>
                    </li>
                    <li class="yui3-u-1-6"><span class="price">8848.00</span></li>
                    <li class="yui3-u-1-6">
                        <span class="number">1</span>
                    </li>
                    <li class="yui3-u-1-8">
                        <a href="#none">重新购买</a>
                        <a href="#none">移到我的关注</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="liked">
            <ul class="sui-nav nav-tabs">
                <li class="active">
                    <a href="#index" data-toggle="tab">猜你喜欢</a>
                </li>
                <li>
                    <a href="#profile" data-toggle="tab">特惠换购</a>
                </li>
            </ul>
            <div class="clearfix"></div>
            <div class="tab-content">
                <div id="index" class="tab-pane active">
                    <div id="myCarousel" data-ride="carousel" data-interval="4000" class="sui-carousel slide">
                        <div class="carousel-inner">
                            <div class="active item">
                                <ul>
                                    <li>
                                        <img src="/static/img/like1.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like2.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like3.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like4.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="item">
                                <ul>
                                    <li>
                                        <img src="/static/img/like1.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like2.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like3.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                    <li>
                                        <img src="/static/img/like4.png" />
                                        <div class="intro">
                                            <i>Apple苹果iPhone 6s (A1699)</i>
                                        </div>
                                        <div class="money">
                                            <span>$29.00</span>
                                        </div>
                                        <div class="incar">
                                            <a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <a href="#myCarousel" data-slide="prev" class="carousel-control left">‹</a>
                        <a href="#myCarousel" data-slide="next" class="carousel-control right">›</a>
                    </div>
                </div>
                <div id="profile" class="tab-pane">
                    <p>特惠选购</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 底部栏位 -->
<!--页面底部-->
<div class="clearfix footer">
    <div class="py-container">
        <div class="footlink">
            <div class="Mod-service">
                <ul class="Mod-Service-list">
                    <li class="grid-service-item intro  intro1">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro2">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro  intro3">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro4">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro intro5">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="clearfix Mod-list">
                <div class="yui3-g">
                    <div class="yui3-u-1-6">
                        <h4>购物指南</h4>
                        <ul class="unstyled">
                            <li>购物流程</li>
                            <li>会员介绍</li>
                            <li>生活旅行/团购</li>
                            <li>常见问题</li>
                            <li>购物指南</li>
                        </ul>

                    </div>
                    <div class="yui3-u-1-6">
                        <h4>配送方式</h4>
                        <ul class="unstyled">
                            <li>上门自提</li>
                            <li>211限时达</li>
                            <li>配送服务查询</li>
                            <li>配送费收取标准</li>
                            <li>海外配送</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>支付方式</h4>
                        <ul class="unstyled">
                            <li>货到付款</li>
                            <li>在线支付</li>
                            <li>分期付款</li>
                            <li>邮局汇款</li>
                            <li>公司转账</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>售后服务</h4>
                        <ul class="unstyled">
                            <li>售后政策</li>
                            <li>价格保护</li>
                            <li>退款说明</li>
                            <li>返修/退换货</li>
                            <li>取消订单</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>特色服务</h4>
                        <ul class="unstyled">
                            <li>夺宝岛</li>
                            <li>DIY装机</li>
                            <li>延保服务</li>
                            <li>品优购E卡</li>
                            <li>品优购通信</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>帮助中心</h4>
                        <img src="/static/img/wx_cz.jpg">
                    </div>
                </div>
            </div>
            <div class="Mod-copyright">
                <ul class="helpLink">
                    <li>关于我们<span class="space"></span></li>
                    <li>联系我们<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>商家入驻<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们</li>
                </ul>
                <p>地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</p>
                <p>京ICP备08001421号京公网安备110108007702</p>
            </div>
        </div>
    </div>
</div>
<!--页面底部END-->

<script type="text/javascript" src="/static/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/plugins/jquery.easing/jquery.easing.min.js"></script>
<script type="text/javascript" src="/static/js/plugins/sui/sui.min.js"></script>
<script type="text/javascript" src="/static/js/widget/nav.js"></script>
</body>

</html>
