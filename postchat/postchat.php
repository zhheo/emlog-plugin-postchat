<?php
/*
Plugin Name: PostChat
Version: 1.0
Plugin URL: https://ai.tianli0.top/
Description: 在页面中插入postchat智能摘要与对话按钮
Author: 张洪Heo
Author URL: https://zhheo.com/
*/

// 防止非法访问
!defined('EMLOG_ROOT') && exit('access deined!');

// 插入 CSS 和 JavaScript 到页面头部
function postchat_add_scripts() {
  // 获取 Storage 实例并读取插件配置信息
  $plugin_storage = Storage::getInstance('PostChat');
  $config = $plugin_storage->getValue('config', array());

  // 获取配置中的 key，如果未设置则使用默认值
  $key = isset($config['key']) ? $config['key'] : '70b649f150276f289d1025508f60c5f58a';
  $postSelector = isset($config['postSelector']) ? $config['postSelector'] : 'postchat_content';
  $title = isset($config['title']) ? $config['title'] : '宇宙无敌智能摘要';
  $backgroundColor = isset($config['backgroundColor']) ? $config['backgroundColor'] : '#3e86f6';
  $bottom = isset($config['bottom']) ? $config['bottom'] : '16px';
  $left = isset($config['left']) ? $config['left'] : '16px';
  $fill = isset($config['fill']) ? $config['fill'] : '#FFFFFF';
  $width = isset($config['width']) ? $config['width'] : '44px';
  $frameWidth = isset($config['frameWidth']) ? $config['frameWidth'] : '375px';
  $frameHeight = isset($config['frameHeight']) ? $config['frameHeight'] : '600px';
  $defaultInput = isset($config['defaultInput']) ? $config['defaultInput'] : true;
  $showInviteLink = isset($config['showInviteLink']) ? $config['showInviteLink'] : true;

  // 动态生成 JavaScript 配置
  echo '<link rel="stylesheet" href="https://ai.tianli0.top/static/public/postChatUser_summary.min.css">
<script>
let tianliGPT_postSelector = "' . htmlspecialchars($postSelector) . '";
let tianliGPT_recommendation = true;
let tianliGPT_Title = "' . htmlspecialchars($title) . '";
var postChatConfig = {
  backgroundColor: "' . htmlspecialchars($backgroundColor) . '",
  bottom: "' . htmlspecialchars($bottom) . '",
  left: "' . htmlspecialchars($left) . '",
  fill: "' . htmlspecialchars($fill) . '",
  width: "' . htmlspecialchars($width) . '",
  frameWidth: "' . htmlspecialchars($frameWidth) . '",
  frameHeight: "' . htmlspecialchars($frameHeight) . '",
  defaultInput: ' . ($defaultInput ? 'true' : 'false') . ',
  showInviteLink: ' . ($showInviteLink ? 'true' : 'false') . ',
  systemType: "emlog"
};
</script>
<script data-postChat_key="' . htmlspecialchars($key) . '" src="https://ai.tianli0.top/static/public/postChatUser_summary.min.js"></script>'."\n";
}

// 为文章内容添加 <postchat_content> 标签
function postchat_add_tag($logData, &$result) {
  // 获取文章内容
  $content = $logData['log_content'];

  // 使用 <postchat_content> 标签包裹文章内容
  $result['log_content'] = '<postchat_content>' . $content . '</postchat_content>';
}

// 在页面头部挂载 CSS 和 JavaScript
addAction('index_head', 'postchat_add_scripts');

addAction('article_content_echo', 'postchat_add_tag');

?>
