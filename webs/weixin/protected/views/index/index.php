<a class="mscBtn on" id="audioBtn" style="cursor:pointer;"></a>
<audio id="bgMusic" src="<?php echo resourceURL('music/default.mp3')?>" autoplay="autoplay" loop="loop"></audio>

<section class="u-arrow">
    <p class="css_sprite01"></p>
</section>
<div class="page-con page2" style="display: none;"></div>
<div class="page-con page3" style="display: none;"></div>
<div class="page-con page4" style="display: none;"></div>
<div class="page-con page5" style="display: none;"></div>
<div class="page-con page6" style="display: none;"></div>
<div class="page-con page7" style="display: none;"></div>
<div class="page-con page8" style="display: none;"></div>
<div class="page-con page9" style="display: none;"></div>
<div class="content">
    <section class="p-ct transformNode-2d">
        <div class="translate-back">
            <div class="m-page">
                <div class="page-con page1"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page2"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page3"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page4"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page5"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page6"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page7"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page8"></div>
            </div>
            <div class="m-page f-hide">
                <div class="page-con page9"></div>
            </div>

        </div>
    </section>
</div>
<div class="buybutton">
    <span><a href="<?php echo $this->createUrl('/order/order')?>">购 买 门 票</a></span>
</div>
<script type="text/javascript"  src="<?php echo resourceURL('scripts/loading.js')?>?time=<?php NOW?>"></script>
