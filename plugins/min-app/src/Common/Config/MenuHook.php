<?php
/**
 * Created by PhpStorm.
 * User: king/QQ:995265288
 * Date: 2019/4/2
 * Time: 4:16 PM
 */

namespace Yunshop\MinApp\Common\Config;


class MenuHook
{
    public static function menu()
    {
        $menu = [
            'name'             => '小程序',
            'type'             => 'tool',
            'url'              => 'plugin.min-app.Backend.Controllers.base-set',
            'url_params'       => '',
            'permit'           => 1,
            'menu'             => 1,
            'top_show'         => 0,
            'left_first_show'  => 1,
            'left_second_show' => 1,
            'icon'             => 'fa-weixin',
            'list_icon'        => 'min_app',
            'parents'          => [],
            'child'            => [
//                'plugin.min-app.admin.audit'     => [
//                    'name'       => '一键发布',
//                    'permit'     => 1,
//                    'menu'       => 1,
//                    'icon'       => '',
//                    'url'        => 'plugin.min-app.Backend.Controllers.audit.index',
//                    'url_params' => '',
//                    'parents'    => ['min-app'],
//                    'child'      => []
//                ],
                'pluginWeChatAppletManualPush'   => [
                    'name'       => '手动发布',
                    'permit'     => 1,
                    'menu'       => 1,
                    'icon'       => '',
                    'url'        => 'plugin.min-app.Backend.Modules.Manual.Controllers.index.index',
                    'url_params' => '',
                    'parents'    => ['min-app'],
                    'item'       => 'pluginWeChatAppletManualPush',
                    'child'      => [
                        'pluginWeChatAppletManualPushLogin'  => [
                            'name'       => '手动发布',
                            'permit'     => 1,
                            'menu'       => 0,
                            'icon'       => '',
                            'url'        => 'plugin.min-app.Backend.Modules.Manual.Controllers.login.index',
                            'url_params' => '',
                            'parents'    => ['min-app', 'pluginWeChatAppletManualPush'],
                            'item'       => 'pluginWeChatAppletManualPushLogin',
                        ],
                        'pluginWeChatAppletManualPushOutput' => [
                            'name'       => '手动发布',
                            'permit'     => 0,
                            'menu'       => 0,
                            'icon'       => '',
                            'url'        => 'plugin.min-app.Backend.Modules.Manual.Controllers.output.index',
                            'url_params' => '',
                            'parents'    => ['min-app', 'pluginWeChatAppletManualPush'],
                            'item'       => 'pluginWeChatAppletManualPushOutput',
                        ]
                    ]
                ],
                'plugin.min-app.admin.set'       => [
                    'name'       => '基础设置',
                    'permit'     => 1,
                    'menu'       => 1,
                    'icon'       => '',
                    'url'        => 'plugin.min-app.Backend.Controllers.base-set.index',
                    'url_params' => '',
                    'parents'    => ['min-app'],
                    'child'      => []
                ],
                'plugin.min-app.admin.assistant' => [
                    'name'       => '小程序数据助手',
                    'permit'     => 1,
                    'menu'       => 1,
                    'icon'       => '',
                    'url'        => 'plugin.min-app.Backend.Controllers.assistant.index',
                    'url_params' => '',
                    'parents'    => ['min-app'],
                    'child'      => []
                ],
            ]
        ];
        return $menu;
    }
}