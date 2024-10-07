<?php

namespace Dcat\Admin\XqwLog;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\XqwLog\Http\Middleware\LogOperation;

class XqwLogServiceProvider extends ServiceProvider
{
    protected $middleware = [
        'middle' => [ // 注册中间件
            LogOperation::class,
        ],
    ];

    // 定义菜单
    protected $menu = [
        [
            'title' => 'Xqw Log',
            'uri'   => 'auth/xqw-logs',
        ],
    ];

	public function settingForm()
	{
		return new Setting($this);
	}
}
