<link rel="stylesheet" href="<?php echo resourceURL('css/bootstrap.min.css')?>?time=<?php NOW?>"/>
<table class="table table-striped default">
    <thead>
        <tr>
        <th>姓名</th>
        <th>手机号码</th>
        <th>购买数量</th>
        <th>总价</th>
        <th>支付方式</th>
        <th>支付渠道</th>
        <th>订单来源</th>
        <th>时间</th>
        <th>产品</th>
        <th>用户昵称</th>
        <th>用户头像</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orderDaos as $orderDao): ?>
            <tr>
                <td><?php echo $orderDao['Frealname']?></td>
                <td><?php echo $orderDao['Fphone']?></td>
                <td><?php echo $orderDao['Fcount']?></td>
                <td><?php echo $orderDao['Fprice_total'] / 100?></td>
                <td>
                <?php
                    $payType = $orderDao['Fpay_type'];
                    if ($payType == \Sucel\Service\Dao\OrderDao::PAY_TYPE_WECHAT ) {
                        echo '微信支付';
                    }
                    else if ($payType == \Sucel\Service\Dao\OrderDao::PAY_TYPE_ALIPAY) {
                        echo '支付宝';
                    }
                 ?>
                </td>
                <td>
                    <?php
                    $payType = $orderDao['Fpay_type'];
                    if ($payType == \Sucel\Service\Dao\OrderDao::PAY_CHANNEL_WEB ) {
                        echo 'H5';
                    }
                    else if ($payType == \Sucel\Service\Dao\OrderDao::PAY_CHANNEL_APP) {
                        echo '手机';
                    }
                    ?>
                </td>
                <td>
                    <?php
                        $from = $orderDao['Ffrom'];
                        if ($from == \Sucel\Service\Dao\OrderDao::FROM_WEIXIN) {
                            echo '微信';
                        }
                        else {
                            //
                        }
                    ?>
                </td>
                <td><?php echo date('Y-m-d H:i', $orderDao['Fcreated'])?></td>
                <?php $productDao = \Sucel\Service\Dao\ProductBaseDao::model()->findByPk($orderDao['Fproduct'])?>
                <td><?php echo $productDao->Fname?></td>
                <?php $userDao = \Sucel\Service\Dao\UserDao::model()->findByPk($orderDao['Fuid']);?>
                <td><?php echo $userDao->Fnickname?></td>
                <td><img src="<?php echo $userDao->Favatar?>" alt=""></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
