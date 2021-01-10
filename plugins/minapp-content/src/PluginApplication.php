<?php

namespace Yunshop\MinappContent;

use Yunshop\MinappContent\services\MinappContentService;

class PluginApplication extends \app\common\services\PluginApplication
{
    protected function setConfig()
    {
    }

    protected function setMenuConfig()
    {
        \app\backend\modules\menu\Menu::current()->setPluginMenu(MinappContentService::get(), [
            'name' => MinappContentService::get('name'),
            'type' => 'tool',
            'url' => 'plugin.minapp-content.admin.acupoint.index', // url 可以填写http 也可以直接写路由
            'url_params' => '', //如果是url填写的是路由则启用参数否则不启用
            'permit' => 1, //如果不设置则不会做权限检测
            'menu' => 1, //如果不设置则不显示菜单，子菜单也将不显示
            'top_show' => 0,
            'left_first_show' => 0,
            'left_second_show' => 1,
            'icon' => '', //菜单图标
            'list_icon' => 'declaration',
            'parents' => [],
            'child' => [
                'acupoint_manage' => [
                    'name' => '穴位管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.acupoint.index',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'acupoint_edit' => [
                            'name' => '编辑穴位',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint.edit',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'acupoint_del' => [
                            'name' => '删除穴位',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint.delete',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'meridian_manage' => [
                            'name' => '经络管理',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.index',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'meridian_edit' => [
                            'name' => '编辑经络',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.edit',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'meridian_del' => [
                            'name' => '删除经络',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.delete',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'course_hour' => [
                            'name' => '经络关联课时列表',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.course-hour',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'course_hour' => [
                            'name' => '经络所属穴位',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.acupoints',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                    ],
                ],
                'sport_clock' => [
                    'name' => '运动打卡管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.sport-clock.step',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'step_exchange_list' => [
                            'name' => '兑换步数列表',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.sport-clock.step-exchange-list',
                            'parents' => ['minapp_content', 'sport_clock'],
                        ],

                    ],
                ],
                'quick_comment_list' => [
                    'name' => '快捷评语管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.quick-comment.index',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'quick_comment_edit' => [
                            'name' => '编辑|添加快捷评语',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.quick-comment.edit',
                            'parents' => ['minapp_content', 'sport_clock'],
                        ],
                        'quick_comment_delete' => [
                            'name' => '删除快捷评语',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.quick-comment.delete',
                            'parents' => ['minapp_content', 'sport_clock'],
                        ],
                        'quick_comment_display' => [
                            'name' => '快捷评语显示|隐藏',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.quick-comment.display',
                            'parents' => ['minapp_content', 'sport_clock'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
