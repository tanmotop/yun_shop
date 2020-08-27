<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2020-02-28
 * Time: 12:45
 *
 *    .--,       .--,
 *   ( (  \.---./  ) )
 *    '.__/o   o\__.'
 *       {=  ^  =}
 *        >  -  <
 *       /       \
 *      //       \\
 *     //|   .   |\\
 *     "'\       /'"_.-~^`'-.
 *        \  _  /--'         `
 *      ___)( )(___
 *     (((__) (__)))     梦之所想,心之所向.
 */
namespace Yunshop\Appletslive\common\services;

use Illuminate\Support\Facades\DB;
use app\common\facades\Setting;
use app\common\exceptions\AppException;

class BaseService
{
    protected $appId;
    protected $secret;

    public function __construct()
    {
        $set = Setting::get('plugin.appletslive');
        if (empty($set)) {
            $wxapp_account = DB::table('account_wxapp')
                ->select('key', 'secret')
                ->where('uniacid', 45)
                ->first();
            $this->appId = $wxapp_account['key'];
            $this->secret = $wxapp_account['secret'];
        } else {
            $this->appId = $set['appId'];
            $this->secret = $set['secret'];
        }
    }

    public function getRooms()
    {
        $token = $this->getToken();
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=' . $token;

        $post_data = [
            'start' => 0,
            'limit' => 100,
        ];

        $result = self::curlPost($url, json_encode($post_data), []);

        return json_decode($result, true);
    }

    public function getReplays($rid)
    {
        $token = $this->getToken();
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=' . $token;

        $post_data = [
            'action' => 'get_replay',
            'room_id' => $rid,
            'start' => 0,
            'limit' => 100,
        ];

        $result = self::curlPost($url, json_encode($post_data), []);

        return json_decode($result, true);
    }

    public function msgSecCheck($content)
    {
        // 文本检测
        $token = $this->getToken();
        $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $token;
        $post_data = ['content' => $content];
        $result = json_decode(self::curlPost($url, json_encode($post_data), []), true);
        if (!$result || !is_array($result) || $result['errcode'] != 0) {
            return $result;
        }
        return true;
    }

    public function textCheck($content)
    {
        $filterStrs = DB::table('diagnostic_service_sns_filter')->get()->toArray();
        $keywords = array();
        foreach ($filterStrs as $k => $v){
            $filterStrs[$k]['content'] = explode('-' , $v['content']);
            if(empty($keywords)){
                $keywords = $filterStrs[$k]['content'];
            }else{
                $keywords = array_merge($keywords, $filterStrs[$k]['content']);
            }
        }
        $keywords = array_unique($keywords);
        $lexicon = array_combine($keywords, array_fill(0, count($keywords), '*')); // 换字符
        $str = strtr($content, $lexicon);                 // 匹配替换
        return $str;
    }

    public function getToken()
    {
        if (empty($this->appId) || empty($this->secret)) {
            throw new AppException('请配置appId和secret');
        }
        $result = self::curlPost($this->requestUrl($this->appId, $this->secret), '', []);
        $decode = json_decode($result, true);
        if ($decode['errcode'] != 0) {
            throw new AppException('appId或者secret错误' . $decode['errmsg']);
        }
        return $decode['access_token'];
    }

    private static function curlPost($url, $post_data, $options = []){
        $ch = curl_init($url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if(!empty($options)){
            curl_setopt_array($ch, $options);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    protected function requestUrl($appId, $secret)
    {
        return 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='. $appId .'&secret=' . $secret;
    }
}