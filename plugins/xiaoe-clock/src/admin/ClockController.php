<?php

namespace Yunshop\XiaoeClock\admin;

use app\backend\modules\goods\models\Goods;
use app\common\components\BaseController;
use app\common\exceptions\AppException;
use app\common\facades\Setting;
use app\common\helpers\PaginationHelper;
use app\common\helpers\Url;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yunshop\Appletslive\common\models\LiveRoom;
use Yunshop\Appletslive\common\models\Replay;
use Yunshop\Appletslive\common\models\Room;
use Yunshop\Appletslive\common\models\RoomComment;
use Yunshop\Appletslive\common\services\CacheService;
use Yunshop\XiaoeClock\models\XiaoeClock;

/**
 * 打卡任务管理控制器
 */
class ClockController extends BaseController
{

    //打卡列表
    public function clock_index()
    {
        $type = request()->get('type', 1);
        if (!in_array($type, [1, 2])) {
            throw new AppException('打卡类型有误');
        }

        $input = \YunShop::request();
        $limit = 20;

        if ($type == 1) { // 日历打卡
            // 处理搜索条件
            $where[] = ['type', '=', 1];
            if (isset($input->search)) {
                $search = $input->search;
                if (intval($search['id']) > 0) {
                    $where[] = ['id', '=', intval($search['id'])];
                }
                if (trim($search['name']) !== '') {
                    $where[] = ['name', 'like', '%' . trim($search['name']) . '%'];
                }
            }

            $list = XiaoeClock::where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);

            if ($list->total() > 0) {
                foreach ($list as &$value) {
                    //总天数,计算总天数
                    $value['count_day'] = floor(($value['end_time'] - $value['start_time']) / 86400);
                    //已经进行天数,计算已经进行
                    $value['pass_day'] = floor((time() - $value['start_time']) / 86400);
                    //管理课程
                    if ($value['join_type'] == 1) {
                        $value['course_id'] = DB::table('yz_appletslive_room')->where('id', $value['course_id'])->first();
                    }
                }
            }

            $pager = PaginationHelper::show($list->total(), $list->currentPage(), $list->perPage());
        }

        if ($type == 2) { // 作业打卡
            // 处理搜索条件
            $where[] = ['type', '=', 2];
            if (isset($input->search)) {
                $search = $input->search;
                if (intval($search['id']) > 0) {
                    $where[] = ['id', '=', intval($search['id'])];
                }
                if (trim($search['name']) !== '') {
                    $where[] = ['name', 'like', '%' . trim($search['name']) . '%'];
                }
            }
            $list = XiaoeClock::where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);
            if ($list->total() > 0) {
                foreach ($list as $k => &$value) {
                    //作业数
                    $value['task_num'] = DB::table('yz_xiaoe_clock_task')->where('clock_id', $value['id'])->count();
                    if ($value['join_type'] == 1) {//关联课程
                        $value['course_id'] = DB::table('yz_appletslive_room')->where('id', $value['course_id'])->first();
                    }
                }
            }
            $pager = PaginationHelper::show($list->total(), $list->currentPage(), $list->perPage());
        }

        return view('Yunshop\XiaoeClock::admin.clock_index', [
            'type' => $type,
            'room_list' => $list,
            'pager' => $pager,
            'request' => $input,
        ])->render();
    }

    //增加打卡
    public function clock_add()
    {
        if (request()->isMethod('post')) {

            $param = request()->all();

            if (!array_key_exists('type', $param) || !in_array($param['type'], [1, 2])) { // 类型
                return $this->message('类型参数有误', Url::absoluteWeb(''), 'danger');
            }
            $ist_data = ['type' => $param['type'], 'sort' => intval($param['sort'])];
            if (array_key_exists('name', $param)) { // 打卡名称
                $ist_data['name'] = $param['name'] ? trim($param['name']) : '';
            }
            if (DB::table('yz_xiaoe_clock')->where('name', $ist_data['name'])->first()) {
                return $this->message('打卡名称已存在', Url::absoluteWeb(''), 'danger');
            }
            if (array_key_exists('cover_img', $param)) { // 打卡封面
                $ist_data['cover_img'] = $param['cover_img'] ? $param['cover_img'] : '';
            }
            if (array_key_exists('text_desc', $param)) { // 打卡图文介绍
                $ist_data['text_desc'] = $param['text_desc'] ? $param['text_desc'] : '';
            }
            if (array_key_exists('video_desc', $param)) { // 打卡视频介绍
                $ist_data['video_desc'] = $param['video_desc'] ? $param['video_desc'] : '';
            }
            if (array_key_exists('audio_desc', $param)) { // 打卡音频介绍
                $ist_data['audio_desc'] = $param['audio_desc'] ? $param['audio_desc'] : '';
            }
            if (array_key_exists('join_type', $param)) { // 打卡方式
                $ist_data['join_type'] = $param['join_type'] ? $param['join_type'] : 0;
            }
            if (array_key_exists('course_id', $param)) { // 管理课程id
                $ist_data['course_id'] = $param['course_id'] ? $param['course_id'] : 0;
            }
            if (array_key_exists('start_time', $param)) { //有效期 开始是日期
                $start_time = $param['start_time'] ? $param['start_time'] : 0;
                if ($start_time != 0) {
                    $ist_data['start_time'] = strtotime($start_time);
                } else {
                    return $this->message('请选择开始日期', Url::absoluteWeb(''), 'danger');
                }
            }
            if (array_key_exists('end_time', $param)) { //有效期 结束是日期
                $end_time = $param['end_time'] ? $param['end_time'] : 0;
                if ($end_time != 0) {
                    $ist_data['end_time'] = strtotime($end_time);
                } else {
                    return $this->message('请选择结束日期', Url::absoluteWeb(''), 'danger');
                }
            }

            if (array_key_exists('text_length', $param)) { //图文长度
                $ist_data['text_length'] = $param['text_length'] ? $param['text_length'] : 5;
            }
            if (array_key_exists('image_length', $param)) { //音频长度
                $ist_data['image_length'] = $param['image_length'] ? $param['image_length'] : 0;
            }
            if (array_key_exists('video_length', $param)) { //视频长度
                $ist_data['video_length'] = $param['video_length'] ? $param['video_length'] : 0;
            }
            if (array_key_exists('display_status', $param)) { //显示状态
                $ist_data['display_status'] = $param['display_status'] ? $param['display_status'] : 1;
            }
            if (array_key_exists('helper_nickname', $param)) { // 助手昵称
                $ist_data['helper_nickname'] = $param['helper_nickname'] ? $param['helper_nickname'] : '';
            }
            if (array_key_exists('helper_avatar', $param)) { //助手头像
                $ist_data['helper_avatar'] = $param['helper_avatar'] ? $param['helper_avatar'] : '';
            }
            if (array_key_exists('helper_wechat', $param)) { //助手微信
                $ist_data['helper_wechat'] = $param['helper_wechat'] ? $param['helper_wechat'] : '';
            }
            if ($param['type'] == 1) {//日历打卡
                if (array_key_exists('valid_time_start', $param)) { //有效时段
                    $ist_data['valid_time_start'] = $param['valid_time_start'] ? $param['valid_time_start'] : 0;
                }
                if (array_key_exists('valid_time_end', $param)) { // 有效时段
                    $ist_data['valid_time_end'] = $param['valid_time_end'] ? $param['valid_time_end'] : 0;
                }
            }
            if ($param['type'] == 2) { //作业打卡
                if (array_key_exists('is_cheat_mode', $param)) { //防作弊
                    $ist_data['is_cheat_mode'] = $param['is_cheat_mode'] ? $param['is_cheat_mode'] : 0;
                }
                if (array_key_exists('is_resubmit', $param)) { //删除原来，重复提交新的 是否允许重新打卡
                    $ist_data['is_resubmit'] = $param['is_resubmit'] ? $param['is_resubmit'] : 0;
                }
            }
            DB::beginTransaction();//开启事务
            $insert_res = DB::table('yz_xiaoe_clock')->insert($ist_data);
            if (!$insert_res) {
                DB::rollBack();//事务回滚
                return $this->message('创建失败', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.clock_index', ['type' => $param['type']]));
            }
            DB::commit();//事务提交
            return $this->message('创建成功', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.clock_index', ['type' => $param['type']]));
        }
        $type = request()->get('type', 0);
        if (!$type) {
            return $this->message('无效的类型', Url::absoluteWeb(''), 'danger');
        }
        return view('Yunshop\XiaoeClock::admin.clock_add', ['type' => $type])->render();
    }

    /**
     * 获取搜索课程
     * @return html
     */
    public function get_search_course()
    {
        $keyword = \YunShop::request()->keyword;
        $where[] = ['type', '=', 1];
        if (trim($keyword) !== '') {
            $where[] = ['name', 'like', '%' . trim($keyword) . '%'];
        }
        $list = Room::select('id', 'name as title', 'cover_img as thumb')->where($where)->get();

        if (!$list->isEmpty()) {
            $goods = set_medias($list->toArray(), array('thumb', 'share_icon'));

        }
        return view('goods.query', [
            'goods' => $goods,
            'exchange' => \YunShop::request()->exchange,
        ])->render();

    }

    // 主题|作业添加
    public function clock_task_add()
    {
        if (request()->isMethod('post')) {
            $param = request()->all();
            $rid = $param['rid'] ? intval($param['rid']) : 0;
            $room = DB::table('yz_xiaoe_clock')->where('id', $rid)->first();
            if (!$room) {
                return $this->message('打卡不存在', Url::absoluteWeb(''), 'danger');
            }
            $type = array_key_exists('type', $param) ? intval($param['type']) : 1;
            if ($type == 1) {//日历主题
                $theme_time = $param['theme_time'] ? $param['theme_time'] : 0;
                $ist_data = [
                    'clock_id' => $rid,
                    'type' => $type,
                    'name' => $param['name'] ? trim($param['name']) : '',
                    'theme_time' => strtotime($theme_time),
                    'cover_img' => $param['cover_img'] ? $param['cover_img'] : '',
                    'text_desc' => $param['text_desc'] ? $param['text_desc'] : '',
                    'video_desc' => $param['video_desc'] ? $param['video_desc'] : '',
                    'sort' => $param['sort'] ? $param['sort'] : 0,
                ];
                if ($type > 0 && DB::table('yz_xiaoe_clock_task')->where('theme_time', $ist_data['theme_time'])->where('id', $rid)->first()) {
                    return $this->message('该日期已经添加主体了', Url::absoluteWeb(''), 'danger');
                }

            } elseif ($type == 2) {//作业

                $start_time = $param['start_time'] ? $param['start_time'] : 0;
                $end_time = $param['end_time'] ? $param['end_time'] : 0;
                $ist_data = [
                    'clock_id' => $rid,
                    'type' => $type,
                    'start_time' => strtotime($start_time),
                    'end_time' => strtotime($end_time),
                    'name' => $param['name'] ? trim($param['name']) : '',
                    'text_desc' => $param['text_desc'] ? $param['text_desc'] : '',
                    'video_desc' => $param['video_desc'] ? $param['video_desc'] : '',
                    'sort' => $param['sort'] ? $param['sort'] : 0,
                ];
            }

            if ($type > 0 && DB::table('yz_xiaoe_clock_task')->where('name', $ist_data['name'])->where('id', $rid)->first()) {
                return $this->message('名称已存在', Url::absoluteWeb(''), 'danger');
            }
            $insert_res = DB::table('yz_xiaoe_clock_task')->insert($ist_data);
            if ($insert_res) {
                return $this->message('添加成功', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.clock_task_list', ['rid' => $rid]));
            } else {
                return $this->message('添加失败', Url::absoluteWeb(''), 'danger');
            }
        }

        $rid = request()->get('rid', 0);
        $clock = DB::table('yz_xiaoe_clock')->where('id', $rid)->first();
        if (!$clock) {
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }

        return view('Yunshop\XiaoeClock::admin.clock_task_add', [
            'clock_id' => $rid,
            'room' => $clock,
        ])->render();
    }

    // 主题|作业列表
    public function clock_task_list()
    {
        $rid = request()->get('rid', 0);
        $room = DB::table('yz_xiaoe_clock')->where('id', $rid)->first();
        $room_type = $room['type'];

        $input = \YunShop::request();
        $limit = 20;

        // 日历主题
        if ($room_type == 1) {

            $where[] = ['clock_id', '=', $rid];

            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['id']) > 0) {
                    $where[] = ['id', '=', intval($search['id'])];
                }
                if (trim($search['title']) !== '') {
                    $where[] = ['title', 'like', '%' . trim($search['title']) . '%'];
                }
                if (trim($search['type']) !== '') {
                    $where[] = ['type', '=', $search['type']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['delete_time', '>', 0];
                    } else {
                        $where[] = ['delete_time', '=', 0];
                    }
                }
            }
            $replay_list = DB::table('yz_xiaoe_clock_task')->where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);
        }

        // 作业
        if ($room_type == 2) {

            $where[] = ['clock_id', '=', $rid];

            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['roomid']) > 0) {
                    $where[] = ['yz_appletslive_liveroom.roomid', '=', intval($search['roomid'])];
                }
                if (trim($search['name']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.name', 'like', '%' . trim($search['name']) . '%'];
                }
                if (trim($search['live_status']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.live_status', '=', $search['live_status']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['yz_appletslive_replay.delete_time', '>', 0];
                    } else {
                        $where[] = ['yz_appletslive_replay.delete_time', '=', 0];
                    }
                }
            }

            $replay_list = DB::table('yz_xiaoe_clock_task')->where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);
        }

        $pager = PaginationHelper::show($replay_list->total(), $replay_list->currentPage(), $replay_list->perPage());

        return view('Yunshop\XiaoeClock::admin.clock_task_list', [
            'rid' => $rid,
            'room_type' => $room_type,
            'replay_list' => $replay_list,
            'pager' => $pager,
            'request' => $input,
        ])->render();
    }

    // 主题|作业编辑
    public function clock_task_edit()
    {
        if (request()->isMethod('post')) {
            $upd_data = [];
            $param = request()->all();
            $id = $param['id'] ? $param['id'] : 0;

            $replay = DB::table('yz_xiaoe_clock_task')->where('id', $id)->first();
            if (!$replay) {
                return $this->message('无效的数据ID', Url::absoluteWeb(''), 'danger');
            }
            if (DB::table('yz_xiaoe_clock_task')->where('name', $upd_data['name'])->where('clock_id', $replay['clock_id'])->where('id', '<>', $id)->first()) {
                return $this->message('名称已存在', Url::absoluteWeb(''), 'danger');
            }

            $type = $replay['type'];
            if ($type == 1) {//日历主题
                $theme_time = $param['theme_time'] ? $param['theme_time'] : 0;
                $upd_data = [
                    'name' => $param['name'] ? trim($param['name']) : '',
                    'theme_time' => strtotime($theme_time),
                    'cover_img' => $param['cover_img'] ? $param['cover_img'] : '',
                    'text_desc' => $param['text_desc'] ? $param['text_desc'] : '',
                    'video_desc' => $param['video_desc'] ? $param['video_desc'] : '',
                    'sort' => $param['sort'] ? $param['sort'] : 0,
                ];

            } elseif ($type == 2) {//作业

                $start_time = $param['start_time'] ? $param['start_time'] : 0;
                $end_time = $param['end_time'] ? $param['end_time'] : 0;
                $upd_data = [
                    'start_time' => strtotime($start_time),
                    'end_time' => strtotime($end_time),
                    'name' => $param['name'] ? trim($param['name']) : '',
                    'text_desc' => $param['text_desc'] ? $param['text_desc'] : '',
                    'video_desc' => $param['video_desc'] ? $param['video_desc'] : '',
                    'sort' => $param['sort'] ? $param['sort'] : 0,
                ];
            }

            $updata_res = DB::table('yz_xiaoe_clock_task')->where('id', $id)->update($upd_data);
            if ($updata_res) {
                return $this->message('保存成功', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.clock_task_list', ['rid' => $replay['clock_id']]));
            } else {
                return $this->message('保存失败', Url::absoluteWeb(''), 'danger');
            }
        }

        $id = request()->get('id', 0);
        $info = DB::table('yz_xiaoe_clock_task')->where('id', $id)->first();
        if (!$info) {
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }

        return view('Yunshop\XiaoeClock::admin.clock_task_edit', [
            'id' => $id,
            'info' => $info,
        ])->render();
    }

    // 用户打卡日记列表
    public function users_clock_list()
    {
        $rid = request()->get('rid', 0);
        $room = DB::table('yz_xiaoe_clock')->where('id', $rid)->first();
        $room_type = $room['type'];

        $input = \YunShop::request();
        $limit = 20;

        // 日历主题
        if ($room_type == 1) {

            $where[] = ['yz_xiaoe_users_clock.clock_id', '=', $rid];
            $where[] = ['yz_xiaoe_users_clock.check_status', '=', 0];
            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['id']) > 0) {
                    $where[] = ['id', '=', intval($search['id'])];
                }
                if (trim($search['title']) !== '') {
                    $where[] = ['title', 'like', '%' . trim($search['title']) . '%'];
                }
                if (trim($search['type']) !== '') {
                    $where[] = ['type', '=', $search['type']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['delete_time', '>', 0];
                    } else {
                        $where[] = ['delete_time', '=', 0];
                    }
                }
            }

            $replay_list = DB::table('yz_xiaoe_users_clock')
                ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_users_clock.user_id')
                ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_users_clock.*')
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);

        }

        // 作业
        if ($room_type == 2) {

            $where[] = ['yz_xiaoe_users_clock.clock_id', '=', $rid];
            $where[] = ['yz_xiaoe_users_clock.check_status', '=', 0];
            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['roomid']) > 0) {
                    $where[] = ['yz_appletslive_liveroom.roomid', '=', intval($search['roomid'])];
                }
                if (trim($search['name']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.name', 'like', '%' . trim($search['name']) . '%'];
                }
                if (trim($search['live_status']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.live_status', '=', $search['live_status']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['yz_appletslive_replay.delete_time', '>', 0];
                    } else {
                        $where[] = ['yz_appletslive_replay.delete_time', '=', 0];
                    }
                }
            }

            $replay_list = DB::table('yz_xiaoe_users_clock')->where($where)
                ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_users_clock.user_id')
                ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_users_clock.*')
                ->orderBy('id', 'desc')
                ->paginate($limit);
        }

        $pager = PaginationHelper::show($replay_list->total(), $replay_list->currentPage(), $replay_list->perPage());

        return view('Yunshop\XiaoeClock::admin.users_clock_list', [
            'rid' => $rid,
            'room_type' => $room_type,
            'replay_list' => $replay_list,
            'pager' => $pager,
            'request' => $input,
        ])->render();
    }

    // 打卡参与的用户 列表
    public function clock_users_list()
    {
        $rid = request()->get('rid', 0);
        $room = DB::table('yz_xiaoe_clock')->where('id', $rid)->first();
        $room_type = $room['type'];

        $input = \YunShop::request();
        $limit = 20;

        // 日历主题
        if ($room_type == 1) {

            $where[] = ['yz_xiaoe_clock_users.clock_id', '=', $rid];

            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['id']) > 0) {
                    $where[] = ['id', '=', intval($search['id'])];
                }
                if (trim($search['title']) !== '') {
                    $where[] = ['title', 'like', '%' . trim($search['title']) . '%'];
                }
                if (trim($search['type']) !== '') {
                    $where[] = ['type', '=', $search['type']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['delete_time', '>', 0];
                    } else {
                        $where[] = ['delete_time', '=', 0];
                    }
                }
            }

            $replay_list = DB::table('yz_xiaoe_clock_users')
                ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_clock_users.user_id')
                ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_clock_users.*')
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate($limit);

        }

        // 作业
        if ($room_type == 2) {

            $where[] = ['yz_xiaoe_clock_users.clock_id', '=', $rid];

            // 处理搜索条件
            if (isset($input->search)) {

                $search = $input->search;
                if (intval($search['roomid']) > 0) {
                    $where[] = ['yz_appletslive_liveroom.roomid', '=', intval($search['roomid'])];
                }
                if (trim($search['name']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.name', 'like', '%' . trim($search['name']) . '%'];
                }
                if (trim($search['live_status']) !== '') {
                    $where[] = ['yz_appletslive_liveroom.live_status', '=', $search['live_status']];
                }
                if (trim($search['status']) !== '') {
                    if ($search['status'] === '0') {
                        $where[] = ['yz_appletslive_replay.delete_time', '>', 0];
                    } else {
                        $where[] = ['yz_appletslive_replay.delete_time', '=', 0];
                    }
                }
            }

            $replay_list = DB::table('yz_xiaoe_clock_users')->where($where)
                ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_clock_users.user_id')
                ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_clock_users.*')
                ->orderBy('id', 'desc')
                ->paginate($limit);
        }

        $pager = PaginationHelper::show($replay_list->total(), $replay_list->currentPage(), $replay_list->perPage());

        return view('Yunshop\XiaoeClock::admin.clock_users_list', [
            'rid' => $rid,
            'room_type' => $room_type,
            'replay_list' => $replay_list,
            'pager' => $pager,
            'request' => $input,
        ])->render();
    }

    //日记详情 日记评论列表
    public function users_clock_detail()
    {
        //日记详情
        $rid = request()->get('id', 0);
        $user_clock_info = DB::table('yz_xiaoe_users_clock')
            ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_users_clock.user_id')
            ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_users_clock.*')
            ->where('yz_xiaoe_users_clock.id', $rid)
            ->first();

        if (!$user_clock_info) {
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }
        if (!empty($user_clock_info['image_desc'])) {
            $user_clock_info['image_desc'] = json_decode($user_clock_info['image_desc']);
        }

        $input = \YunShop::request();
        $limit = 20;

        // 日历评论列表
        $where[] = ['yz_xiaoe_users_clock_comment.clock_users_id', '=', $rid];
        $where[] = ['yz_xiaoe_users_clock_comment.check_status', '=', 0];
        $replay_list = DB::table('yz_xiaoe_users_clock_comment')
            ->join('diagnostic_service_user', 'diagnostic_service_user.ajy_uid', '=', 'yz_xiaoe_users_clock_comment.user_id')
            ->select('diagnostic_service_user.nickname', 'diagnostic_service_user.avatar', 'yz_xiaoe_users_clock_comment.*')
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($limit);

        $pager = PaginationHelper::show($replay_list->total(), $replay_list->currentPage(), $replay_list->perPage());

        return view('Yunshop\XiaoeClock::admin.users_clock_detail', [
            'rid' => $rid,
            'user_clock_info' => $user_clock_info,
            'replay_list' => $replay_list,
            'pager' => $pager,
            'request' => $input,
        ])->render();
    }

    public function users_clock_del()
    {
        $input = request()->all();

        if (!array_key_exists('id', $input)) { //id
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }
        $replay = DB::table('yz_xiaoe_users_clock')->where('id', intval($input['id']))->first();
        if (!$replay) {
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }
        $up_data['check_status'] = 2;
        $up_data['deleted_at'] = time();
        //删除评论
        $del_res = DB::table('yz_xiaoe_users_clock')->where('id', $replay['id'])->update($up_data);

        // 刷新接口数据缓存
        if ($del_res) {
            return $this->message('删除成功', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.users_clock_detail', ['id' => $replay['clock_users_id']]));
        } else {
            return $this->message('删除失败', Url::absoluteWeb(''), 'danger');
        }
    }

//   评论删除
    public function users_clock_comment_del()
    {

        $input = request()->all();

        if (!array_key_exists('id', $input)) { //id
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }
        $replay = DB::table('yz_xiaoe_users_clock_comment')->where('id', intval($input['id']))->first();
        if (!$replay) {
            return $this->message('数据不存在', Url::absoluteWeb(''), 'danger');
        }
        $up_data['check_status'] = 2;
        $up_data['deleted_at'] = time();
        //删除评论
        $del_res = DB::table('yz_xiaoe_users_clock_comment')->where('id', $replay['id'])->update($up_data);

        // 刷新接口数据缓存
        if ($del_res) {
            return $this->message('删除成功', Url::absoluteWeb('plugin.xiaoe-clock.admin.clock.users_clock_detail', ['id' => $replay['clock_users_id']]));
        } else {
            return $this->message('删除失败', Url::absoluteWeb(''), 'danger');
        }
    }
}