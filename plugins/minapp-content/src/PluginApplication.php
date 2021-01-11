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
                        'meridian_acupoints' => [
                            'name' => '经络所属穴位',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.acupoints',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'meridian_acupoints' => [
                            'name' => '经络所属穴位',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.meridian.acupoints',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'acupoint_reply_manage' => [
                            'name' => '穴位笔记管理',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint-replys.index',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'acupoint_reply_post' => [
                            'name' => '穴位笔记评论',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint-replys.post',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'acupoint_reply_status' => [
                            'name' => '穴位笔记状态管理',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint-replys.status',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                        'acupoint_reply_delete' => [
                            'name' => '删除穴位笔记',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.acupoint-replys.delete',
                            'parents' => ['minapp_content', 'acupoint_manage'],
                        ],
                    ],
                ],
                'feedback_manage' => [
                    'name' => '用户反馈',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.feedback.index',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'feedback_detail' => [
                            'name' => '反馈详情列表',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.feedback.msg',
                            'parents' => ['minapp_content', 'feedback_manage'],
                        ],
                        'feedback_delete' => [
                            'name' => '删除反馈详情',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.feedback.delete',
                            'parents' => ['minapp_content', 'feedback_manage'],
                        ],
                    ],
                ],
                'article_manage' => [
                    'name' => '文章管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.article-category.index',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'article_category_manage' => [
                            'name' => '文章分类管理',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.article-category.index',
                            'parents' => ['minapp_content', 'article_manage'],
                        ],
                        'article_category_edit' => [
                            'name' => '编辑文章分类',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.article-category.edit',
                            'parents' => ['minapp_content', 'article_manage'],
                        ],
                        'article_category_del' => [
                            'name' => '删除文章分类',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.article-category.delete',
                            'parents' => ['minapp_content', 'article_manage'],
                        ],
                    ],
                ],
                'banner' => [
                    'name' => '轮播图管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.banner.index',
                    'url_params' => '',
                    'parents' => ['minapp_content'],
                    'child' => [
                        'custom_share_add' => [
                            'name' => '添加',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.banner.add',
                            'parents' => ['minapp_content', 'custom_share_list'],
                        ],
                        'custom_share_edit' => [
                            'name' => '编辑',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.banner.edit',
                            'parents' => ['minapp_content', 'custom_share_list'],
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
                            'parents' => ['minapp_content', 'quick_comment_list'],
                        ],
                        'quick_comment_delete' => [
                            'name' => '删除快捷评语',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.quick-comment.delete',
                            'parents' => ['minapp_content', 'quick_comment_list'],
                        ],
                        'quick_comment_display' => [
                            'name' => '快捷评语显示|隐藏',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.quick-comment.display',
                            'parents' => ['minapp_content', 'quick_comment_list'],
                        ],
                    ],
                ],
                'custom_share_list' => [
                    'name' => '自定义分享管理',
                    'permit' => 1,
                    'menu' => 1,
                    'icon' => '',
                    'url' => 'plugin.minapp-content.admin.custom-share.index',
                    'url_params' => '',
                    'parents' => ['minapp_content','custom_share_list'],
                    'child' => [
                        'custom_share_edit' => [
                            'name' => '编辑|添加自定义分享',
                            'permit' => 1,
                            'menu' => 0,
                            'url' => 'plugin.minapp-content.admin.custom-share.edit',
                            'parents' => ['minapp_content', 'custom_share_list'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
