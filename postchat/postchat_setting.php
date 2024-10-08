<?php

!defined('EMLOG_ROOT') && exit('access deined!');

function plugin_setting_view() {
    // 获取 Storage 实例
    $db = Storage::getInstance("PostChat");

    // 如果是 POST 请求，保存表单中的设置
    if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
        $db->setValue('config', array(
            'key'             => $_POST['postchat_key'],
            'enableSummary'   => isset($_POST['postchat_enableSummary']) ? true : false,
            'enableAI'        => isset($_POST['postchat_enableAI']) ? true : false,
            'postSelector'    => $_POST['postchat_postSelector'],
            'title'           => $_POST['postchat_title'],
            'summaryStyle'    => $_POST['postchat_summaryStyle'],
            'backgroundColor' => $_POST['postchat_backgroundColor'],
            'bottom'          => $_POST['postchat_bottom'],
            'left'            => $_POST['postchat_left'],
            'fill'            => $_POST['postchat_fill'],
            'width'           => $_POST['postchat_width'],
            'frameWidth'      => $_POST['postchat_frameWidth'],
            'frameHeight'     => $_POST['postchat_frameHeight'],
            'defaultInput'    => isset($_POST['postchat_defaultInput']) ? true : false,
            'showInviteLink'  => isset($_POST['postchat_showInviteLink']) ? true : false
        ), 'array');
    }

    // 获取当前配置项，使用默认值（如果配置项还未设置）
    $config = $db->getValue('config', array());

    // 如果某个配置项不存在，则使用默认值
    $key = isset($config['key']) ? $config['key'] : '70b649f150276f289d1025508f60c5f58a';
    $enableSummary = isset($config['enableSummary']) ? $config['enableSummary'] : false;
    $enableAI = isset($config['enableAI']) ? $config['enableAI'] : false;
    $postSelector = isset($config['postSelector']) ? $config['postSelector'] : 'postchat_content';
    $title = isset($config['title']) ? $config['title'] : '文章摘要';
    $summaryStyle = isset($config['summaryStyle']) ? $config['summaryStyle'] : 'https://ai.tianli0.top/static/public/postChatUser_summary.min.css';
    $backgroundColor = isset($config['backgroundColor']) ? $config['backgroundColor'] : '#3e86f6';
    $bottom = isset($config['bottom']) ? $config['bottom'] : '16px';
    $left = isset($config['left']) ? $config['left'] : '16px';
    $fill = isset($config['fill']) ? $config['fill'] : '#FFFFFF';
    $width = isset($config['width']) ? $config['width'] : '44px';
    $frameWidth = isset($config['frameWidth']) ? $config['frameWidth'] : '375px';
    $frameHeight = isset($config['frameHeight']) ? $config['frameHeight'] : '600px';
    $defaultInput = isset($config['defaultInput']) ? $config['defaultInput'] : true;
    $showInviteLink = isset($config['showInviteLink']) ? $config['showInviteLink'] : true;

    // 显示配置页面
    ?>
    <div class="plugin-settings">
        <form action="" method="post">
            <h3>账户配置</h3>
            <div>
                <label>账户KEY:</label>
                <input type="text" name="postchat_key" value="<?php echo htmlspecialchars((string)$key); ?>">
                <small>使用PostChat的用户请前往 https://ai.tianli0.top/ 获取 KEY。</small>
            </div>
            
            <h3>文章摘要配置</h3>
            <div>
                <label>开启文章摘要:</label>
                <input type="checkbox" name="postchat_enableSummary" <?php echo $enableSummary ? 'checked' : ''; ?>>
            </div>
            <div>
                <label>文章选择器:</label>
                <input type="text" name="postchat_postSelector" value="<?php echo htmlspecialchars((string)$postSelector); ?>">
                <small>用于选择文章内容的CSS选择器。如果使用的不是默认主题需要进行更改。</small>
            </div>
            <div>
                <label>摘要标题:</label>
                <input type="text" name="postchat_title" value="<?php echo htmlspecialchars((string)$title); ?>">
            </div>
            <div>
                <label>摘要样式CSS:</label>
                <input type="text" name="postchat_summaryStyle" value="<?php echo htmlspecialchars((string)$summaryStyle); ?>">
                <small>自定义摘要的CSS样式。</small>
            </div>
            
            <h3>聊天助手配置</h3>
            <div>
                <label>开启PostChat智能对话:</label>
                <input type="checkbox" name="postchat_enableAI" <?php echo $enableAI ? 'checked' : ''; ?>>
            </div>
            <div>
                <label>按钮背景颜色:</label>
                <input type="text" name="postchat_backgroundColor" value="<?php echo htmlspecialchars((string)$backgroundColor); ?>">
            </div>
            <div>
                <label>按钮图标填充颜色:</label>
                <input type="text" name="postchat_fill" value="<?php echo htmlspecialchars((string)$fill); ?>">
            </div>
            <div>
                <label>按钮距离底部边距:</label>
                <input type="text" name="postchat_bottom" value="<?php echo htmlspecialchars((string)$bottom); ?>">
            </div>
            <div>
                <label>按钮距离左侧边距:</label>
                <input type="text" name="postchat_left" value="<?php echo htmlspecialchars((string)$left); ?>">
            </div>
            <div>
                <label>按钮宽度:</label>
                <input type="text" name="postchat_width" value="<?php echo htmlspecialchars((string)$width); ?>">
            </div>
            <div>
                <label>聊天框架宽度:</label>
                <input type="text" name="postchat_frameWidth" value="<?php echo htmlspecialchars((string)$frameWidth); ?>">
            </div>
            <div>
                <label>聊天框架高度:</label>
                <input type="text" name="postchat_frameHeight" value="<?php echo htmlspecialchars((string)$frameHeight); ?>">
            </div>
            <div>
                <label>默认输入:</label>
                <input type="checkbox" name="postchat_defaultInput" <?php echo $defaultInput ? 'checked' : ''; ?>>
                <small>用户点击按钮后自动输入本页面标题。</small>
            </div>
            <div>
                <label>显示邀请链接:</label>
                <input type="checkbox" name="postchat_showInviteLink" <?php echo $showInviteLink ? 'checked' : ''; ?>>
                <small>勾选此项以显示邀请链接。</small>
            </div>
            
            <input type="submit" value="保存配置">
        </form>
    </div>
    <?php
}
?>
