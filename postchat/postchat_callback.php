<?php

!defined('EMLOG_ROOT') && exit('access deined!');

// 当插件被激活时执行的函数
function plugin_active_postchat() {
    $defaultConfig = array(
        'key'             => '70b649f150276f289d1025508f60c5f58a',
        'enableSummary'   => false,
        'enableAI'        => false,
        'postSelector'    => 'postchat_content',
        'title'           => '文章摘要',
        'summaryStyle'    => 'https://ai.tianli0.top/static/public/postChatUser_summary.min.css',
        'backgroundColor' => '#3e86f6',
        'bottom'          => '16px',
        'left'            => '16px',
        'fill'            => '#FFFFFF',
        'width'           => '44px',
        'frameWidth'      => '375px',
        'frameHeight'     => '600px',
        'defaultInput'    => true,
        'showInviteLink'  => true
    );

    // 循环设置每个默认配置项，确保它们被存入
    foreach ($defaultConfig as $key => $value) {
        if (Option::get('postchat_' . $key) === null) {
            Option::set('postchat_' . $key, $value);
        }
    }
}

// 当插件被停用时执行的函数
function plugin_inactive_postchat() {
    // 通常情况下，不需要在插件停用时做任何事情
}

// 当插件被卸载时执行的函数
function plugin_rm_postchat() {
    // 删除所有相关的配置项
    $keys = array(
        'key', 'enableSummary', 'enableAI', 'postSelector', 'title',
        'summaryStyle', 'backgroundColor', 'bottom', 'left', 'fill',
        'width', 'frameWidth', 'frameHeight', 'defaultInput', 'showInviteLink'
    );

    foreach ($keys as $key) {
        Option::delete('postchat_' . $key);
    }
}

// 注册激活、停用、卸载的回调
addAction('plugin_active_postchat', 'plugin_active_postchat');
addAction('plugin_inactive_postchat', 'plugin_inactive_postchat');
addAction('plugin_rm_postchat', 'plugin_rm_postchat');

?>
