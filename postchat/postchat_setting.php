<?php

!defined('EMLOG_ROOT') && exit('access denied!');

function plugin_setting_view() {
    // 获取 Storage 实例
    $db = Storage::getInstance("PostChat");

    // 如果是 POST 请求，保存表单中的设置
    if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
        $db->setValue('config', array(
            'key'             => $_POST['postchat_key'],
            'apiSecret'       => $_POST['postchat_apiSecret'],
            'enableSummary'   => isset($_POST['postchat_enableSummary']) ? true : false,
            'enablePrivateSummary' => isset($_POST['postchat_enablePrivateSummary']) ? true : false,
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
            'hotWords'             => isset($_POST['postchat_hotWords']) ? true : false,
            'recommend'            => min(10, max(0, intval($_POST['postchat_recommend'] ?? 0))),
            'tianliGPT_podcast'    => isset($_POST['postchat_tianliGPT_podcast']) ? true : false
        ), 'array');
    }

    // 获取当前配置项，使用默认值（如果配置项还未设置）
    $config = $db->getValue('config', array());

    // 如果某个配置项不存在，则使用默认值
    $key = isset($config['key']) ? $config['key'] : '70b649f150276f289d1025508f60c5f58a';
    $apiSecret = isset($config['apiSecret']) ? $config['apiSecret'] : '';
    $enableSummary = isset($config['enableSummary']) ? $config['enableSummary'] : false;
    $enablePrivateSummary = isset($config['enablePrivateSummary']) ? $config['enablePrivateSummary'] : false;
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
    $recommend = isset($config['recommend']) ? min(10, max(0, intval($config['recommend']))) : 0;
    $tianliGPT_podcast = isset($config['tianliGPT_podcast']) ? $config['tianliGPT_podcast'] : false;

    // 显示配置页面
    ?>
    <div class="plugin-settings" style="max-width: 800px; margin: 20px auto; padding: 20px;">
        <style>
            .plugin-settings {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
            .settings-section {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 24px;
                margin-bottom: 24px;
            }
            .settings-section h3 {
                color: #1a1a1a;
                font-size: 1.25rem;
                margin: 0 0 20px 0;
                padding-bottom: 12px;
                border-bottom: 2px solid #f0f0f0;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 500;
            }
            .form-group input[type="text"],
            .form-group input[type="number"],
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.2s;
            }
            .form-group input[type="text"]:focus,
            .form-group input[type="number"]:focus,
            .form-group select:focus,
            .form-group textarea:focus {
                border-color: #3e86f6;
                outline: none;
                box-shadow: 0 0 0 2px rgba(62,134,246,0.1);
            }
            .form-group small {
                display: block;
                margin-top: 6px;
                color: #666;
                font-size: 12px;
            }
            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .checkbox-group input[type="checkbox"] {
                width: 16px;
                height: 16px;
            }
            .submit-btn {
                background: #3e86f6;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .submit-btn:hover {
                background: #2d6fd9;
            }
            .form-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
            }
        </style>

        <form action="" method="post">
            <div class="settings-section">
                <h3>账户配置</h3>
                <div class="form-group">
                    <label>项目KEY</label>
                    <input type="text" name="postchat_key" value="<?php echo htmlspecialchars((string)$key); ?>">
                    <small>使用PostChat的用户请前往 <a href="https://ai.zhheo.com/" target="_blank">https://ai.zhheo.com/</a> 获取 KEY。</small>
                </div>
                <div class="form-group">
                    <label>API Secret</label>
                    <input type="text" name="postchat_apiSecret" value="<?php echo htmlspecialchars((string)$apiSecret); ?>">
                    <small>请前往 <a href="https://ai.zhheo.com/console/settings" target="_blank">https://ai.zhheo.com/console/settings</a> 获取 API Secret。</small>
                </div>
            </div>

            <div class="settings-section">
                <h3>文章摘要配置</h3>
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_enableSummary" id="enableSummary" <?php echo $enableSummary ? 'checked' : ''; ?>>
                            <label for="enableSummary">开启文章摘要</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_enablePrivateSummary" id="enablePrivateSummary" <?php echo $enablePrivateSummary ? 'checked' : ''; ?>>
                            <label for="enablePrivateSummary">开启私有化摘要</label>
                        </div>
                        <small>开启私有化摘要后，摘要将写入到本地数据库，提升访问速度。开启此项需要填写API Secret。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>文章选择器</label>
                        <input type="text" name="postchat_postSelector" value="<?php echo htmlspecialchars((string)$postSelector); ?>">
                        <small>用于选择文章内容的CSS选择器。如果使用的不是默认主题需要进行更改。</small>
                    </div>
                    <div class="form-group">
                        <label>摘要标题</label>
                        <input type="text" name="postchat_title" value="<?php echo htmlspecialchars((string)$title); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>摘要样式CSS</label>
                        <input type="text" name="postchat_summaryStyle" value="<?php echo htmlspecialchars((string)$summaryStyle); ?>">
                        <small>自定义摘要的CSS样式。</small>
                    </div>
                    <div class="form-group">
                        <label>摘要主题配置</label>
                        <input type="text" name="postchat_summaryTheme" value="<?php echo htmlspecialchars((string)$summaryTheme); ?>">
                        <small>切换文章摘要主题，详情请见 <a href="https://postchat.zhheo.com/theme.html" target="_blank">主题文档</a>。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>文章路由</label>
                        <input type="text" name="postchat_postURL" value="<?php echo htmlspecialchars((string)$postURL); ?>">
                        <small>在符合url条件的网页执行文章摘要功能。</small>
                    </div>
                    <div class="form-group">
                        <label>黑名单</label>
                        <input type="text" name="postchat_blacklist" value="<?php echo htmlspecialchars((string)$blacklist); ?>">
                        <small>填写相关的json地址，帮助文档：<a href="https://ai.zhheo.com/docs/variant.html#tianligpt-blacklist" target="_blank">黑名单参数</a>。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>字数限制</label>
                        <input type="text" name="postchat_wordLimit" value="<?php echo htmlspecialchars((string)$wordLimit); ?>">
                        <small>可以设置提交的字数限制，默认为1000字。</small>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_typingAnimate" id="typingAnimate" <?php echo $typingAnimate ? 'checked' : ''; ?>>
                            <label for="typingAnimate">打字动画效果</label>
                        </div>
                        <small>模拟流处理的打字效果。</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>自定义摘要开头文本</label>
                    <input type="text" name="postchat_beginningText" value="<?php echo htmlspecialchars((string)$beginningText); ?>">
                    <small>默认为"这篇文章介绍了"，你可以自定义开头语。</small>
                </div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="postchat_tianliGPT_podcast" id="tianliGPT_podcast" <?php echo $tianliGPT_podcast ? 'checked' : ''; ?>>
                        <label for="tianliGPT_podcast">启用 AI 播客 (tianliGPT_podcast)</label>
                    </div>
                    <small>开启后启用 AI 播客功能，默认关闭。</small>
                </div>
            </div>

            <div class="settings-section">
                <h3>聊天助手配置</h3>
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_enableAI" id="enableAI" <?php echo $enableAI ? 'checked' : ''; ?>>
                            <label for="enableAI">开启PostChat智能对话</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>显示模式</label>
                        <select name="postchat_userMode">
                            <option value="magic" <?php echo $userMode === 'magic' ? 'selected' : ''; ?>>Magic</option>
                            <option value="iframe" <?php echo $userMode === 'iframe' ? 'selected' : ''; ?>>Iframe</option>
                        </select>
                        <small>选择PostChat的显示模式。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>按钮背景颜色</label>
                        <input type="text" name="postchat_backgroundColor" value="<?php echo htmlspecialchars((string)$backgroundColor); ?>">
                    </div>
                    <div class="form-group">
                        <label>按钮图标填充颜色</label>
                        <input type="text" name="postchat_fill" value="<?php echo htmlspecialchars((string)$fill); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>按钮距离底部边距</label>
                        <input type="text" name="postchat_bottom" value="<?php echo htmlspecialchars((string)$bottom); ?>">
                    </div>
                    <div class="form-group">
                        <label>按钮距离左侧边距</label>
                        <input type="text" name="postchat_left" value="<?php echo htmlspecialchars((string)$left); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>按钮宽度</label>
                        <input type="text" name="postchat_width" value="<?php echo htmlspecialchars((string)$width); ?>">
                    </div>
                    <div class="form-group">
                        <label>聊天框架宽度</label>
                        <input type="text" name="postchat_frameWidth" value="<?php echo htmlspecialchars((string)$frameWidth); ?>">
                        <small>聊天框架宽度，默认375px。（仅在iframe模式下有效）</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>聊天框架高度</label>
                        <input type="text" name="postchat_frameHeight" value="<?php echo htmlspecialchars((string)$frameHeight); ?>">
                        <small>聊天框架高度，默认600px。（仅在iframe模式下有效）</small>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_defaultInput" id="defaultInput" <?php echo $defaultInput ? 'checked' : ''; ?>>
                            <label for="defaultInput">默认输入</label>
                        </div>
                        <small>用户点击按钮后自动输入本页面标题。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_showInviteLink" id="showInviteLink" <?php echo $showInviteLink ? 'checked' : ''; ?>>
                            <label for="showInviteLink">显示邀请链接</label>
                        </div>
                        <small>勾选此项后，用户点击聊天助手会跳转到有邀请性质的洪墨AI界面，购买会获得返利，具体规则请到 <a href="https://ai.zhheo.com/console/invitation" target="_blank">前往</a> 查看。</small>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_upLoadWeb" id="upLoadWeb" <?php echo $upLoadWeb ? 'checked' : ''; ?>>
                            <label for="upLoadWeb">上传网站内容</label>
                        </div>
                        <small>勾选此项时，你的网站内容将会被自动提交到PostChat。</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>界面标题</label>
                        <input type="text" name="postchat_userTitle" value="<?php echo htmlspecialchars((string)$userTitle); ?>">
                    </div>
                    <div class="form-group">
                        <label>聊天界面描述</label>
                        <input type="text" name="postchat_userDesc" value="<?php echo htmlspecialchars((string)$userDesc); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="postchat_addButton" id="addButton" <?php echo $addButton ? 'checked' : ''; ?>>
                            <label for="addButton">是否显示按钮</label>
                        </div>
                        <small>勾选此项时按钮会被显示。</small>
                    </div>
                    <div class="form-group">
                        <label>用户图标</label>
                        <input type="text" name="postchat_userIcon" value="<?php echo htmlspecialchars((string)$userIcon); ?>">
                        <small>Magic模式下显示的用户图标URL。（仅在Magic模式下有效）</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>默认聊天问题</label>
                    <textarea name="postchat_defaultChatQuestions" rows="4"><?php echo htmlspecialchars(implode("\n", (array)$defaultChatQuestions)); ?></textarea>
                    <small>每行一个问题，作为默认的聊天问题选项。（仅在Magic模式下有效）</small>
                </div>
                <div class="form-group">
                    <label>默认搜索问题</label>
                    <textarea name="postchat_defaultSearchQuestions" rows="4"><?php echo htmlspecialchars(implode("\n", (array)$defaultSearchQuestions)); ?></textarea>
                    <small>每行一个问题，作为默认的搜索问题选项。（仅在Magic模式下有效）</small>
                </div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="postchat_hotWords" id="hotWords" <?php echo $hotWords ? 'checked' : ''; ?>>
                        <label for="hotWords">开启热词功能</label>
                    </div>
                    <small>开启后将在聊天界面显示文章热词。</small>
                </div>
                <div class="form-group">
                    <label>文章底部推荐数量 (recommend)</label>
                    <input type="number" name="postchat_recommend" value="<?php echo (int)$recommend; ?>" min="0" max="10" step="1">
                    <small>在文章底部显示推荐文章的数量。0 为关闭，最大 10。设为 0 则不显示推荐文章。</small>
                </div>
            </div>

            <button type="submit" class="submit-btn">保存配置</button>
        </form>
    </div>
    <?php
}
?>
