<?php
/**
 * Created by PhpStorm.
 * User: zlt
 * Date: 2020/10/22
 * Time: 10:00
 */

namespace app\frontend\modules\live\controllers;

use app\common\components\BaseController;
use app\common\facades\Setting;
use app\common\services\tencentlive\IMService;
use app\common\models\live\ImCallbackLog;
use app\common\services\tencentlive\LiveSetService;
use Yunshop\Appletslive\common\services\BaseService;

class IMCallbackController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //$requestBody = file_get_contents("php://input");
        $req_data = request()->input();
        $input_data = request()->getContent();
        \Log::debug('IMCallback req_data:' . json_encode($req_data, 320) . ' input_data:' . $input_data);
        if ($req_data['SdkAppid'] != LiveSetService::getIMSetting('sdk_appid')) {
            return $this->responJson(4002, 'error', 'illegal SdkAppid!');
        }

        if (!empty($input_data)) {
            $callback_data = $input_data;
            $input_data = json_decode($input_data, true);
            $_model = new ImCallbackLog();
            $type_str = substr($input_data['CallbackCommand'], 0, strpos($input_data['CallbackCommand'], '.'));
            $data = [
                'uniacid' => \YunShop::app()->uniacid,
                'sdk_appid' => $req_data['SdkAppid'],
                'type' => $_model->getType($type_str),
                'callback_command' => $input_data['CallbackCommand'],
                'callback_data' => $callback_data,
                'client_iP' => $req_data['ClientIP'],
                'created_at' => time()
            ];
            $extra = [];
            if (in_array($input_data['CallbackCommand'], ['Group.CallbackAfterSendMsg'])) {
                foreach ($input_data['MsgBody'] as $v){
                    $insert_data = array_merge($data, $this->getMsgData($input_data,$v,$_model));
                    $_model->fill($insert_data)->save();
                }
            } elseif (in_array($input_data['CallbackCommand'], ['Group.CallbackBeforeSendMsg'])) {
                $extra['MsgBody'] = [];
                foreach ($input_data['MsgBody'] as $v){

                    $insert_data = array_merge($data, $this->getMsgData($input_data,$v,$_model));
                    \Log::info('Group.CallbackBeforeSendMsg' . json_encode($insert_data));
                    $text = $this->filterMsg($v['MsgContent']['Text']);
                    \Log::info('Group.CallbackBeforeSendMsg' . $text);
                    $_model->fill($insert_data)->save();
                    $extra['MsgBody'][] = [
                        "MsgType" => $input_data['MsgBody'][0]['MsgType'], // 文本
                        "MsgContent" => [
                            "Text" => $text
                        ]
                    ];
                }
            }else{
                return $this->responJson();
            }
            return $this->responJson(0, 'OK', '', $extra);
        } else {
            return $this->responJson(4001, 'error', 'body empty!');
        }

    }

    protected function getMsgData($input_data,$msg_body, $_model)
    {
        $msg_data = [
            'group_id' => $input_data['GroupId'],
            'from_account' => $input_data['From_Account'],
            'Operator_Account' => empty($input_data['Operator_Account']) ? '' : $input_data['Operator_Account'],
            'msg_time' => $input_data['MsgTime'],
            'msg_type' => $_model->getMsgType($msg_body['MsgType']),
            'msg_content' => $msg_body['MsgContent']['Text'],
        ];
        return $msg_data;
    }

    protected function responJson($ErrorCode = 0, $ActionStatus = 'OK', $ErrorInfo = '', $extra = [])
    {
        $res_data = [
            "ActionStatus" => $ActionStatus,
            "ErrorInfo" => $ErrorInfo,
            "ErrorCode" => $ErrorCode
        ];
        if($extra){
            $resp_data = array_merge($res_data, $extra);
        }
        return response()->json($resp_data, 200, ['charset' => 'utf-8']);
    }

    protected function filterMsg($text)
    {
        $_model = new BaseService();
        $res_json = json_decode($text);
        if($res_json){
            $res_json->text = $_model->textCheck($res_json->text,false);
            return json_encode($res_json,320);
        }else{
            return $_model->textCheck($text,false);
        }
    }

}