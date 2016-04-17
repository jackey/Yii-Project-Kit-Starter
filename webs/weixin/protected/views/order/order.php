<?php $firstProductSet = array_values($product['set'])[0]; ?>

<div class="submit-order-container">
    <div class="advertise"></div>
    <div class="advertiseinfo">
        <br/>
        <br/>

        <p class="title">活动时间：</p>
        <br/>

        <p class="info">2015年12月18日 - 2015年12月20日</p>
        <br/>

        <p class="info">10:00时 - 18:00时</p>
        <br/>
        <br/>

        <p class="title">活动地点：</p>
        <br/>

        <p class="info">上海市黄陂南路751号卓维700园区1号楼</p>
        <br/>
        <br/>

        <div class="choosecontent">
            <p class="title">选择日期：</p>

            <div class="choose">
                <?php foreach ($product['set'] as $index => $productSet): ?>
                    <span class="choosebutton <?php if ($index == 0) echo 'active' ?>"
                          data-id="<?php echo $productSet['psid'] ?>"><?php echo date('m月d日', strtotime($productSet['date'])) ?></span>
                <?php endforeach; ?>
            </div>
            <p class="title">购买数量：</p>

            <div class="choose">
                <div class="minus">-</div>
                <div class="number">1</div>
                <div class="plus">+</div>
                <span class="money">共计：<span style="color: #ff6417;">￥<span class="price_cnt"><?php echo $firstProductSet['price']/100 ?></span></span></span>
            </div>
        </div>
        <div class="buynow">立 即 购 买</div>
        <div class="record"><a href="<?php echo $this->createUrl('ticket')?>">我 的 门 票</a></div>
        <div style="float: left;width: 100%;height: 15px;"></div> 
    </div>
    <div class="grey"></div>

    <div class="detailinfo">
        <div class="detailinput">
            <label for="name">姓名：</label>
            <input type="text" name="realname" class="inputinfo"/>
        </div>
        <div class="detailinput">
            <label for="phone">电话：</label>
            <input type="number" name="phone" class="inputinfo"/>
        </div>
        <div class="tip">为保障您的合法权益，请留下您的相关信息，以便客服联系到您</div>
        <div class="pay btn-buy">去 支 付</div>
    </div>

    <input type="text" style="display: none;" name="date" value="<?php echo $firstProductSet['psid'] ?>" />

    <input type="text" style="display: none;" name="price" value="<?php echo $firstProductSet['price'] ?>" />

    <input type="text" style="display: none;" name="count" value="1">

</div>

