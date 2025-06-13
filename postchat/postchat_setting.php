<?php

!defined('EMLOG_ROOT') && exit('access denied!');

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
            'summaryTheme'    => $_POST['postchat_summaryTheme'],
            'postURL'         => $_POST['postchat_postURL'],
            'blacklist'       => $_POST['postchat_blacklist'],
            'wordLimit'       => $_POST['postchat_wordLimit'],
            'typingAnimate'   => isset($_POST['postchat_typingAnimate']) ? true : false,
            'backgroundColor' => $_POST['postchat_backgroundColor'],
            'bottom'          => $_POST['postchat_bottom'],
            'left'            => $_POST['postchat_left'],
            'fill'            => $_POST['postchat_fill'],
            'width'           => $_POST['postchat_width'],
            'frameWidth'      => $_POST['postchat_frameWidth'],
            'frameHeight'     => $_POST['postchat_frameHeight'],
            'defaultInput'    => isset($_POST['postchat_defaultInput']) ? true : false,
            'showInviteLink'  => isset($_POST['postchat_showInviteLink']) ? true : false,
            'beginningText'   => $_POST['postchat_beginningText'],
            'upLoadWeb'       => isset($_POST['postchat_upLoadWeb']) ? true : false,
            'userTitle'       => $_POST['postchat_userTitle'],
            'userDesc'        => $_POST['postchat_userDesc'],
            'addButton'       => isset($_POST['postchat_addButton']) ? true : false,
            'userMode'        => $_POST['postchat_userMode'],
            'userIcon'        => $_POST['postchat_userIcon'],
            'defaultChatQuestions'  => explode("\n", trim($_POST['postchat_defaultChatQuestions'])),
            'defaultSearchQuestions'=> explode("\n", trim($_POST['postchat_defaultSearchQuestions'])),
            'hotWords'             => isset($_POST['postchat_hotWords']) ? true : false
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
    $summaryStyle = isset($config['summaryStyle']) ? $config['summaryStyle'] : 'https://ai.zhheo.com/static/public/postChatUser_summary.min.css';
    $summaryTheme = isset($config['summaryTheme']) ? $config['summaryTheme'] : 'default';
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
    $upLoadWeb = isset($config['upLoadWeb']) ? $config['upLoadWeb'] : true;
    $userTitle = isset($config['userTitle']) ? $config['userTitle'] : 'PostChat';
    $userDesc = isset($config['userDesc']) ? $config['userDesc'] : '如果你对网站的内容有任何疑问，可以来问我哦～';
    $addButton = isset($config['addButton']) ? $config['addButton'] : true;
    $userMode = isset($config['userMode']) ? $config['userMode'] : 'magic';
    $userIcon = isset($config['userIcon']) ? $config['userIcon'] : 'https://ai.zhheo.com/static/img/PostChat.webp';
    $defaultChatQuestions = isset($config['defaultChatQuestions']) ? $config['defaultChatQuestions'] : array();
    $defaultSearchQuestions = isset($config['defaultSearchQuestions']) ? $config['defaultSearchQuestions'] : array();
    $hotWords = isset($config['hotWords']) ? $config['hotWords'] : true;

    // 显示配置页面
    ?>
    <div class="plugin-settings" style="display: flex; flex-direction: column; gap: 20px; max-width: 600px; margin: 0 auto;">
        <form action="" method="post" style="display: flex; flex-direction: column; gap: 20px;">
            <h3>账户配置</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>账户KEY:</label>
                <input type="text" name="postchat_key" value="<?php echo htmlspecialchars((string)$key); ?>">
                <small>使用PostChat的用户请前往 https://ai.zhheo.com/ 获取 KEY。</small>
            </div>
            
            <h3>文章摘要配置</h3>
            <div style="display: flex; gap: 10px;">
                <label>开启文章摘要:</label>
                <input type="checkbox" name="postchat_enableSummary" <?php echo $enableSummary ? 'checked' : ''; ?>>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>文章选择器:</label>
                <input type="text" name="postchat_postSelector" value="<?php echo htmlspecialchars((string)$postSelector); ?>">
                <small>用于选择文章内容的CSS选择器。如果使用的不是默认主题需要进行更改。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>摘要标题:</label>
                <input type="text" name="postchat_title" value="<?php echo htmlspecialchars((string)$title); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>摘要样式CSS:</label>
                <input type="text" name="postchat_summaryStyle" value="<?php echo htmlspecialchars((string)$summaryStyle); ?>">
                <small>自定义摘要的CSS样式。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>摘要主题配置:</label>
                <input type="text" name="postchat_summaryTheme" value="<?php echo htmlspecialchars((string)$summaryTheme); ?>">
                <small>切换文章摘要主题，详情请见 <a href="https://postchat.zhheo.com/theme.html" target="_blank">主题文档</a>。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>文章路由:</label>
                <input type="text" name="postchat_postURL" value="<?php echo htmlspecialchars((string)$postURL); ?>">
                <small>在符合url条件的网页执行文章摘要功能。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>黑名单:</label>
                <input type="text" name="postchat_blacklist" value="<?php echo htmlspecialchars((string)$blacklist); ?>">
                <small>填写相关的json地址，帮助文档：<a href="https://ai.zhheo.com/docs/variant.html#tianligpt-blacklist" target="_blank">黑名单参数</a>。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>字数限制:</label>
                <input type="text" name="postchat_wordLimit" value="<?php echo htmlspecialchars((string)$wordLimit); ?>">
                <small>可以设置提交的字数限制，默认为1000字。</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <label>打字动画效果:</label>
                <input type="checkbox" name="postchat_typingAnimate" <?php echo $typingAnimate ? 'checked' : ''; ?>>
                <small>模拟流处理的打字效果。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>自定义摘要开头文本:</label>
                <input type="text" name="postchat_beginningText" value="<?php echo htmlspecialchars((string)$beginningText); ?>">
                <small>默认为"这篇文章介绍了"，你可以自定义开头语。</small>
            </div>
            
            <h3>聊天助手配置</h3>
            <div style="display: flex; gap: 10px;">
                <label>开启PostChat智能对话:</label>
                <input type="checkbox" name="postchat_enableAI" <?php echo $enableAI ? 'checked' : ''; ?>>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>显示模式:</label>
                <select name="postchat_userMode">
                    <option value="magic" <?php echo $userMode === 'magic' ? 'selected' : ''; ?>>Magic</option>
                    <option value="iframe" <?php echo $userMode === 'iframe' ? 'selected' : ''; ?>>Iframe</option>
                </select>
                <small>选择PostChat的显示模式。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>按钮背景颜色:</label>
                <input type="text" name="postchat_backgroundColor" value="<?php echo htmlspecialchars((string)$backgroundColor); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>按钮图标填充颜色:</label>
                <input type="text" name="postchat_fill" value="<?php echo htmlspecialchars((string)$fill); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>按钮距离底部边距:</label>
                <input type="text" name="postchat_bottom" value="<?php echo htmlspecialchars((string)$bottom); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>按钮距离左侧边距:</label>
                <input type="text" name="postchat_left" value="<?php echo htmlspecialchars((string)$left); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>按钮宽度:</label>
                <input type="text" name="postchat_width" value="<?php echo htmlspecialchars((string)$width); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>聊天框架宽度:</label>
                <input type="text" name="postchat_frameWidth" value="<?php echo htmlspecialchars((string)$frameWidth); ?>">
                <small>聊天框架宽度，默认375px。（仅在iframe模式下有效）</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>聊天框架高度:</label>
                <input type="text" name="postchat_frameHeight" value="<?php echo htmlspecialchars((string)$frameHeight); ?>">
                <small>聊天框架高度，默认600px。（仅在iframe模式下有效）</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <label>默认输入:</label>
                <input type="checkbox" name="postchat_defaultInput" <?php echo $defaultInput ? 'checked' : ''; ?>>
                <small>用户点击按钮后自动输入本页面标题。</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <label>显示邀请链接:</label>
                <input type="checkbox" name="postchat_showInviteLink" <?php echo $showInviteLink ? 'checked' : ''; ?>>
                <small>勾选此项以显示邀请链接。</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <label>上传网站内容:</label>
                <input type="checkbox" name="postchat_upLoadWeb" <?php echo $upLoadWeb ? 'checked' : ''; ?>>
                <small>勾选此项时，你的网站内容将会被自动提交到PostChat。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>界面标题:</label>
                <input type="text" name="postchat_userTitle" value="<?php echo htmlspecialchars((string)$userTitle); ?>">
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>聊天界面描述:</label>
                <input type="text" name="postchat_userDesc" value="<?php echo htmlspecialchars((string)$userDesc); ?>">
            </div>
            <div style="display: flex; gap: 10px;">
                <label>是否显示按钮:</label>
                <input type="checkbox" name="postchat_addButton" <?php echo $addButton ? 'checked' : ''; ?>>
                <small>勾选此项时按钮会被显示。</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>用户图标:</label>
                <input type="text" name="postchat_userIcon" value="<?php echo htmlspecialchars((string)$userIcon); ?>">
                <small>Magic模式下显示的用户图标URL。（仅在Magic模式下有效）</small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>默认聊天问题:</label>
                <textarea name="postchat_defaultChatQuestions" rows="4"><?php echo htmlspecialchars(implode("\n", (array)$defaultChatQuestions)); ?></textarea>
                <small>每行一个问题，作为默认的聊天问题选项。（仅在Magic模式下有效）  </small>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label>默认搜索问题:</label>
                <textarea name="postchat_defaultSearchQuestions" rows="4"><?php echo htmlspecialchars(implode("\n", (array)$defaultSearchQuestions)); ?></textarea>
                <small>每行一个问题，作为默认的搜索问题选项。（仅在Magic模式下有效）</small>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <label>开启热词功能:</label>
                <input type="checkbox" name="postchat_hotWords" <?php echo $hotWords ? 'checked' : ''; ?>>
                <small>开启后将在聊天界面显示文章热词。</small>
            </div>
            
            <input type="submit" value="保存配置" style="align-self: flex-start;">
        </form>
    </div>
    <?php
}
?>
