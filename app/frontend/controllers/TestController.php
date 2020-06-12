<?php

namespace app\frontend\controllers;

use app\common\components\BaseController;
use app\common\events\order\AfterOrderPaidEvent;
use app\common\models\Option;
use app\common\models\Order;
use app\common\modules\goods\GoodsRepository;
use app\common\modules\option\OptionRepository;
use app\frontend\models\Goods;
use app\Jobs\addGoodsCouponQueueJob;
use Yunshop\Love\Modules\Goods\GoodsLoveRepository;

class TestController extends BaseController
{
    public function test(){
//        return json([1,2,3]);
        $queueData = [
            'uniacid' => \YunShop::app()->uniacid,
            'goods_id' => 'laoge001',
            'uid' => 'uid-123321',
            'coupon_id' => 'coupon-456654',
            'send_num' => '123',
            'end_send_num' => 0,
            'status' => 0,
            'created_at' => time()
        ];
        $this->dispatch((new addGoodsCouponQueueJob($queueData)));
        return json([1,2,3]);
    }
    public function index()
    {
        $order = Order::find(45751);
        event(new AfterOrderPaidEvent($order));
//        $goods = Goods::find(1175);
//        $goods->reduceStock(1);
        return $this->successJson();
//        $goods->stock = max($goods->stock - 1, 0);
//        $goods->save();
//        $stock = file_get_contents(storage_path('app/test'));
//        $stock = max($stock - 1, 0);
//        file_put_contents(storage_path('app/test'), $stock . PHP_EOL);

    }
}