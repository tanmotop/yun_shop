<?php
/**
 * Created by PhpStorm.
 * User: shenyang
 * Date: 2019/1/18
 * Time: 5:49 PM
 */
return array(
    [
        'key' => 'singleEnoughReduce',
        'class' => \app\common\helpers\Serializer::serialize(function (\app\frontend\modules\order\models\PreOrder $preOrder) {
            return new \app\frontend\modules\order\discount\SingleEnoughReduce($preOrder);
        }),
    ], [
        'key' => 'enoughReduce',
        'class' => \app\common\helpers\Serializer::serialize(function (\app\frontend\modules\order\models\PreOrder $preOrder) {
            return new \app\frontend\modules\order\discount\EnoughReduce($preOrder);
        }),
    ],
    [
        'key' => 'couponDiscount',
        'class' => \app\common\helpers\Serializer::serialize(function (\app\frontend\modules\order\models\PreOrder $preOrder) {
            return new \app\frontend\modules\order\discount\CouponDiscount($preOrder);
        }),
    ]
);