<?php
/****************************************************************
 * Author:  libaojia
 * Date:    2017/9/26 下午1:44
 * Email:   livsyitian@163.com
 * QQ:      995265288
 * User:    芸众商城 www.yunzshop.com
 ****************************************************************/

namespace app\frontend\modules\live\controllers;


use app\backend\modules\tracking\models\DiagnosticServiceUser;
use app\common\components\ApiController;
use app\common\facades\Setting;
use app\common\services\tencentlive\LiveSetService;
use app\frontend\models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use app\common\models\live\CloudLiveRoom;
use app\common\services\tencentlive\LiveService;
use app\common\services\tencentlive\IMService;
use app\common\models\live\ImCallbackLog;

class LiveRoomController extends ApiController
{

    const PAGE_SIZE = 10;

    protected $ignoreAction = ['index','detail','getMsgList','sendMsg','getLiveList'];

    public function index()
    {
        $_model = new LiveService();
        return $this->successJson('调试接口',$_model->getDescribeLiveStreamState('ajygroup-3'));
//        return $this->successJson('调试接口',$_model->dropLiveStream(3));
        $im_service = new IMService();
//        return $this->successJson('调试接口',$im_service->getGroupList());
//        return $this->successJson('调试接口',$im_service->getGroupInfo('ajygroup-3'));
//        return $this->successJson('调试接口',$im_service->getGroupMsg('ajygroup-3'));
//        return $this->successJson('调试接口',IMService::getSign());
        return $this->successJson('调试接口',$im_service->sendGroupMsg('ajygroup-3','This is test ' . date('Y-m-d H:i:s')));
    }

    public function getSign(){
        $member_id = \YunShop::app()->getMemberId();
        if(!empty($member_id)){
            return $this->successJson('获取签名成功',['usersig'=>IMService::getSign($member_id),'member_id'=>$member_id]);
        }else{
            return $this->errorJson('请登录');
        }

    }

    public function sendMsg(){
        $im_service = new IMService();
        $id = request()->id ? request()->id : 3;
        $msg = request()->text ? request()->text : 'This is test ' . date('Y-m-d H:i:s');
        $_model = CloudLiveRoom::where('id',$id)->first();
        return $this->successJson('调试接口',$im_service->sendGroupMsg($_model->group_id ,$msg));
    }

    public function getLiveList(){
        $page_size = request()->get('pagesize');
        $page_size = $page_size ? $page_size : self::PAGE_SIZE;
        $status = request()->get('status',0);

        $live_service = new LiveService();
        $list = $live_service->getLiveList($page_size,$status);
        return $this->successJson('获取直播间列表成功', $list);
    }

    public function detail()
    {
        $id = intval(request()->id);

        if (!$id) {
            return  $this->errorJson('直播间ID为空');
        }

        $_model = CloudLiveRoom::where('id',$id)->first();
        if (!$_model) {
            return $this->errorJson('未获取到直播间');
        }
        $_model->push_url = '';

        $im_service = new IMService();
        $_model->online_num = $im_service->getOnlineMemberNum($_model->group_id);

        return $this->successJson('获取直播间信息成功！',$_model->toArray());
    }

    public function getMemberInfo(){
        $uid = intval(request()->uid);

        if (!$uid) {
            return  $this->errorJson('会员ID为空');
        }

        $member_info = DiagnosticServiceUser::where('ajy_uid',$uid)->select('ajy_uid as uid','nickname','avatar','avatarUrl')->first();

        if($member_info){
            return $this->successJson('获取会员信息成功！',$member_info);
        }else{
            return  $this->errorJson('会员ID为空');
        }
    }

    public function getMsgList(){
        $id = intval(request()->id);

        if (!$id) {
            return  $this->errorJson('直播间ID为空');
        }

        $_model = CloudLiveRoom::where('id',$id)->first();
        if (!$_model) {
            return $this->errorJson('未获取到直播间');
        }

        $pageSize = \YunShop::request()->get('pagesize');
        $pageSize = $pageSize ? $pageSize : self::PAGE_SIZE;

        $list = ImCallbackLog::uniacid()->where([['callback_command','=','Group.CallbackAfterSendMsg'],['group_id','=',$_model->group_id],['sdk_appid','=',LiveSetService::getIMSetting('sdk_appid')]])->orderby('id','desc')->paginate($pageSize)->toArray();
        if (empty($list['data'])) {
            return $this->errorJson('没有找到消息记录');
        }else{
            return $this->successJson('获取消息记录成功', $list);
        }
    }

}