<div class="myticket">
    <div class="tickettitle">
        我 的 门 票
    </div>
    <div class="ticketlist clearfix">
        <?php foreach ($tickets as $ticket):?>
            <div class="ticket">
                <div class="ticketleft">
                    <h1 style="font-size: 22px;">途印记</h1>
                    <br>
                    <h3>活动时间：</h3>
                    <p>2015年12月18日 - 2015年12月20日</p>
                    <p>10:00 - 18:00</p>
                    <br>
                    <h3>活动地点：</h3>
                    <p>上海市黄陂南路751号卓维700园区1号楼</p>
                </div>
                <div class="ticketright">
                    <img src="<?php echo $ticket['qrcode']?>" alt="" class="codeimg">
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="white close">
    </div>
    <div class="bigcodeimg close">
        <img src="" alt="">
    </div>
</div>