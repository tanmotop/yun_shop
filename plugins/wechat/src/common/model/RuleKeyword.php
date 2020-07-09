<?php
/**
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2017/6/6
 * Time: 下午9:09
 */

namespace Yunshop\Wechat\common\model;

class RuleKeyword extends \app\common\modules\wechat\models\RuleKeyword
{
    // 通过rid获取多个关键字id
    public static function getRuleKeywordIdsByRid($rid)
    {
        return static::select('id')->uniacid()->where('module',Rule::WECHAT_MODULE)->where('rid',$rid)->get();
    }
    // 通过rid获取多个关键字对象
    public static function getRuleKeywordsByRid($rid)
    {
        return static::uniacid()->where('module',Rule::WECHAT_MODULE)->where('rid',$rid)->get();
    }
    // 获取所有关键字
    public static function getRuleKeywords()
    {
        return static::uniacid()->where('module',Rule::WECHAT_MODULE)->get();
    }

    // 通过id获取模型对象
    public static function getRuleKeywordById($id)
    {
        return static::uniacid()->where('module',Rule::WECHAT_MODULE)->find($id);
    }
    // 通过id获取模型对象
    public static function getKeywordsInfo($id)
    {
        if (empty($id)) {
            return null;
        } else {
            return static::uniacid()->where('module',Rule::WECHAT_MODULE)->select('id','content','rid')->with('hasOneRule')->find($id);
        }
    }
    // 获取所有关键字
    public static function searchRuleKeywords($search)
    {
        if (!empty($search)) {
            return static::uniacid()
                ->where('status','=',1)
                ->where('content','like',$search.'%')
                ->orderBy('id','desc')
                ->get();
        }
        return static::uniacid()->where('status','=',1)->orderBy('id','desc')->get();
    }
    // 通过id删除对象
    public static function deleteRuleKeywordById($id)
    {
        $keyword = static::getRuleKeywordById($id);
        if ($keyword) {
            if ($keyword->delete()) {
                return ['status'=>1,'message'=>'删除成功!','data'=>[]];
            } else {
                return ['status'=>0,'message'=>'关键字'.$keyword->id.'删除失败!','data'=>[]];
            }
        }
        return ['status'=>0,'message'=>'关键字'.$id.'不存在,删除失败!','data'=>[]];
    }

    // 保存和修改
    public static function saveRuleKeyword($form)
    {
        if (empty($form['id'])) {
            $keyword = new self();
        } else {
            $keyword = static::uniacid()->where('module',Rule::WECHAT_MODULE)->find($form['id']);
            if (empty($keyword)) {
                return ['status' => 0, 'message' => '关键字不存在或属于其他插件!ID:'.$form['id'], 'data' => []];
            }
        }
        // 填充
        $keyword->fill($form);
        // 验证数据
        $validate = $keyword->validator();
        if ($validate->fails()) {
            return ['status' => 0, 'message' => $validate->messages(), 'data' => []];
        }
        if ($keyword->save()) {
            return ['status' => 1, 'message' => '关键字保存成功!', 'data' => []];
        }
        return ['status' => 0, 'message' => '关键字保存失败!', 'data' => []];
    }

    // 通过关键字获取规则
    public static function getRuleByKeywords($keywords)
    {
        // 先找精准触发
        $accurate = static::uniacid()
            ->where('module',Rule::WECHAT_MODULE)
            ->where('status','=',1)
            ->where('content','=',$keywords)
            ->where('type','=',1)
            ->orderBy('displayorder','desc')
            ->first();

        // 再找模糊查询,正则匹配先不考虑
        if (empty($accurate)) {
            return static::uniacid()
                ->where('module',Rule::WECHAT_MODULE)
                ->where('status','=',1)
                ->where('content','like',$keywords.'%')
                ->where('type','!=',1)
                ->orderBy('displayorder','desc')
                ->first();
        } else {
            return $accurate;
        }
    }



}