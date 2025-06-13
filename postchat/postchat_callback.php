<?php

!defined('EMLOG_ROOT') && exit('access denied!');

// 当插件被激活时执行的函数
function plugin_active_postchat() {
    $defaultConfig = array(
        'key'             => '70b649f150276f289d1025508f60c5f58a',
        'enableSummary'   => false,
        'enableAI'        => false,
        'postSelector'    => 'postchat_content',
        'title'           => '文章摘要',
        'summaryStyle'    => 'https://ai.zhheo.com/static/public/postChatUser_summary.min.css',
        'summaryTheme'    => 'default',
        'postURL'         => '*',
        'blacklist'       => '',
        'wordLimit'       => '1000',
        'typingAnimate'   => true,
        'backgroundColor' => '#3e86f6',
        'bottom'          => '16px',
        'left'            => '16px',
        'fill'            => '#FFFFFF',
        'width'           => '44px',
        'frameWidth'      => '375px',
        'frameHeight'     => '600px',
        'defaultInput'    => true,
        'showInviteLink'  => true,
        'beginningText'   => '这篇文章介绍了',
        'upLoadWeb'       => true,
        'userTitle'       => 'PostChat',
        'userDesc'        => '如果你对网站的内容有任何疑问，可以来问我哦～',
        'addButton'       => true,
        'userMode'        => 'magic',
        'userIcon'        => 'https://ai.zhheo.com/static/img/PostChat.webp',
        'defaultChatQuestions' => array(),
        'defaultSearchQuestions' => array()
    );

    // 循环设置每个默认配置项，确保它们被存入
    foreach ($defaultConfig as $key => $value) {
        if (Option::get('postchat_' . $key) === null) {
            Option::set('postchat_' . $key, $value);
        }
    }

    // 调用初始化函数创建数据表
    callback_init();
}

// 当插件被停用时执行的函数
function plugin_inactive_postchat() {
    // 通常情况下，不需要在插件停用时做任何事情
}

// 当插件被卸载时执行的函数
function plugin_rm_postchat() {
    // 删除所有相关的配置项
    $keys = array(
        'key',
        'enableSummary',
        'enableAI',
        'postSelector',
        'title',
        'summaryStyle',
        'summaryTheme',
        'postURL',
        'blacklist',
        'wordLimit',
        'typingAnimate',
        'backgroundColor',
        'bottom',
        'left',
        'fill',
        'width',
        'frameWidth',
        'frameHeight',
        'defaultInput',
        'showInviteLink',
        'beginningText',
        'upLoadWeb',
        'userTitle',
        'userDesc',
        'addButton',
        'userMode',
        'userIcon',
        'defaultChatQuestions',
        'defaultSearchQuestions'
    );

    foreach ($keys as $key) {
        Option::delete('postchat_' . $key);
    }
}

// 注册激活、停用、卸载的回调
addAction('plugin_active_postchat', 'plugin_active_postchat');
addAction('plugin_inactive_postchat', 'plugin_inactive_postchat');
addAction('plugin_rm_postchat', 'plugin_rm_postchat');

// 插件开启时调用，创建数据表
function callback_init() {
    $db = Database::getInstance();
    $charset = 'utf8mb4';
    $prefix = DB_PREFIX;
    
    // 创建摘要表
    $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}postchat_summary` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `log_id` int(11) NOT NULL COMMENT '文章ID',
        `summary` text NOT NULL COMMENT '摘要内容',
        `generate_time` datetime NOT NULL COMMENT '生成时间',
        `is_vector` varchar(20) NOT NULL DEFAULT 'generating' COMMENT '向量状态',
        PRIMARY KEY (`id`),
        KEY `log_id` (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset};";
    
    $db->query($sql);
}

// 插件删除时调用，清理数据表
function callback_rm() {
    $db = Database::getInstance();
    $prefix = DB_PREFIX;
    $db->query("DROP TABLE IF EXISTS `{$prefix}postchat_summary`");
}

// 插件更新时调用
function callback_up() {
    // 暂无更新操作
}

?>
