<?php
/*
Plugin Name: PostChat
Version: 3.1.0
Plugin URL: https://ai.zhheo.com/
Description: 在页面中插入postchat智能摘要与对话按钮
Author: 张洪Heo
Author URL: https://zhheo.com/
*/

// 防止非法访问
!defined('EMLOG_ROOT') && exit('access denied!');

// 引入公共函数
require_once(EMLOG_ROOT . '/content/plugins/postchat/postchat_common.php');

// 引入摘要生成相关函数
require_once(EMLOG_ROOT . '/content/plugins/postchat/postchat_summary.php');

// 插入 CSS 和 JavaScript 到页面头部
function postchat_add_scripts() {
    write_log("开始加载页面脚本");
    // 获取 Storage 实例并读取插件配置信息
    $plugin_storage = Storage::getInstance('PostChat');
    $config = $plugin_storage->getValue('config', array());

    // 获取配置中的各项参数，如果未设置则使用默认值
    $key = isset($config['key']) ? $config['key'] : '70b649f150276f289d1025508f60c5f58a';
    $postSelector = isset($config['postSelector']) ? $config['postSelector'] : 'postchat_content';
    $title = isset($config['title']) ? $config['title'] : '宇宙无敌智能摘要';
    $postURL = isset($config['postURL']) ? $config['postURL'] : '*';
    $blacklist = isset($config['blacklist']) ? $config['blacklist'] : '';
    $wordLimit = isset($config['wordLimit']) ? $config['wordLimit'] : '1000';
    $typingAnimate = isset($config['typingAnimate']) ? $config['typingAnimate'] : true;
    $backgroundColor = isset($config['backgroundColor']) ? $config['backgroundColor'] : '#3e86f6';
    $bottom = isset($config['bottom']) ? $config['bottom'] : '16px';
    $left = isset($config['left']) ? $config['left'] : '16px';
    $fill = isset($config['fill']) ? $config['fill'] : '#FFFFFF';
    $width = isset($config['width']) ? $config['width'] : '44px';
    $frameWidth = isset($config['frameWidth']) ? $config['frameWidth'] : '375px';
    $frameHeight = isset($config['frameHeight']) ? $config['frameHeight'] : '600px';
    $defaultInput = isset($config['defaultInput']) ? $config['defaultInput'] : true;
    $showInviteLink = isset($config['showInviteLink']) ? $config['showInviteLink'] : true;
    $beginningText = isset($config['beginningText']) ? $config['beginningText'] : '这篇文章介绍了';
    $enableAI = isset($config['enableAI']) ? $config['enableAI'] : true;
    $summaryTheme = isset($config['summaryTheme']) ? $config['summaryTheme'] : 'default';
    $upLoadWeb = isset($config['upLoadWeb']) ? $config['upLoadWeb'] : true;
    $userTitle = isset($config['userTitle']) ? $config['userTitle'] : 'PostChat';
    $userDesc = isset($config['userDesc']) ? $config['userDesc'] : '如果你对网站的内容有任何疑问，可以来问我哦～';
    $addButton = isset($config['addButton']) ? $config['addButton'] : true;
    $userMode = isset($config['userMode']) ? $config['userMode'] : 'magic';
    $userIcon = isset($config['userIcon']) ? $config['userIcon'] : 'https://ai.zhheo.com/static/img/PostChat.webp';
    $defaultChatQuestions = isset($config['defaultChatQuestions']) ? $config['defaultChatQuestions'] : array();
    $defaultSearchQuestions = isset($config['defaultSearchQuestions']) ? $config['defaultSearchQuestions'] : array();
    $hotWords = isset($config['hotWords']) ? $config['hotWords'] : true;
    $recommend = isset($config['recommend']) ? min(10, max(0, intval($config['recommend']))) : 0;
    $tianliGPT_podcast = isset($config['tianliGPT_podcast']) ? $config['tianliGPT_podcast'] : false;

    // 从 URL 参数中获取文章 ID
    $logid = isset($_GET['post']) ? intval($_GET['post']) : 0;

    // 动态生成 JavaScript 配置
    echo '<link rel="stylesheet" href="https://ai.zhheo.com/static/public/postChatUser_summary.min.css">
    <script>
    let tianliGPT_key = "' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_postSelector = "' . htmlspecialchars($postSelector, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_Title = "' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_postURL = "' . htmlspecialchars($postURL, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_blacklist = "' . htmlspecialchars($blacklist, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_wordLimit = "' . htmlspecialchars($wordLimit, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_typingAnimate = ' . ($typingAnimate ? 'true' : 'false') . ';
    let tianliGPT_theme = "' . htmlspecialchars($summaryTheme, ENT_QUOTES, 'UTF-8') . '";
    let tianliGPT_summary = "' . ($logid > 0 ? get_summary($logid) : '') . '";
    let tianliGPT_podcast = ' . ($tianliGPT_podcast ? 'true' : 'false') . ';
    var postChatConfig = {
      backgroundColor: "' . htmlspecialchars($backgroundColor, ENT_QUOTES, 'UTF-8') . '",
      bottom: "' . htmlspecialchars($bottom, ENT_QUOTES, 'UTF-8') . '",
      left: "' . htmlspecialchars($left, ENT_QUOTES, 'UTF-8') . '",
      fill: "' . htmlspecialchars($fill, ENT_QUOTES, 'UTF-8') . '",
      width: "' . htmlspecialchars($width, ENT_QUOTES, 'UTF-8') . '",
      frameWidth: "' . htmlspecialchars($frameWidth, ENT_QUOTES, 'UTF-8') . '",
      frameHeight: "' . htmlspecialchars($frameHeight, ENT_QUOTES, 'UTF-8') . '",
      defaultInput: ' . ($defaultInput ? 'true' : 'false') . ',
      showInviteLink: ' . ($showInviteLink ? 'true' : 'false') . ',
      beginningText: "' . addslashes(htmlspecialchars($beginningText, ENT_QUOTES, 'UTF-8')) . '",
      addButton: ' . ($addButton ? 'true' : 'false') . ',
      upLoadWeb: ' . ($upLoadWeb ? 'true' : 'false') . ',
      userTitle: "' . htmlspecialchars($userTitle, ENT_QUOTES, 'UTF-8') . '",
      userDesc: "' . htmlspecialchars($userDesc, ENT_QUOTES, 'UTF-8') . '",
      userMode: "' . htmlspecialchars($userMode, ENT_QUOTES, 'UTF-8') . '",
      userIcon: "' . htmlspecialchars($userIcon, ENT_QUOTES, 'UTF-8') . '",
      defaultChatQuestions: ' . json_encode($defaultChatQuestions) . ',
      defaultSearchQuestions: ' . json_encode($defaultSearchQuestions) . ',
      hotWords: ' . ($hotWords ? 'true' : 'false') . ',
      recommend: ' . (int)$recommend . ',
      systemType: "emlog"
    };
    </script>
    <script data-postChat_key="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '" src="https://ai.zhheo.com/static/public/postChatUser_summary.min.js"></script>' . "\n";
}

// 为文章内容添加 <postchat_content> 标签
function postchat_add_tag($logData, &$result) {
    write_log("处理文章内容，文章ID: " . (isset($logData['gid']) ? $logData['gid'] : 'unknown'));
    // 获取文章内容
    $content = $logData['log_content'];

    // 使用 <postchat_content> 标签包裹文章内容
    $result['log_content'] = '<postchat_content>' . $content . '</postchat_content>';
}

// 在页面头部挂载 CSS 和 JavaScript
addAction('index_head', 'postchat_add_scripts');

// 在文章内容输出前添加 <postchat_content> 标签
addAction('article_content_echo', 'postchat_add_tag');

write_log("PostChat插件初始化完成");
?>
