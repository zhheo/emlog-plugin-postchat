<?php
!defined('EMLOG_ROOT') && exit('access denied!');

// 引入公共函数
require_once(EMLOG_ROOT . '/content/plugins/postchat/postchat_common.php');

// 生成摘要的函数
function generate_summary($log_id, $title, $content) {
    write_log("开始生成摘要，文章ID: {$log_id}");
    
    // 获取配置
    $db = Storage::getInstance("PostChat");
    $config = $db->getValue('config', array());
    $key = isset($config['key']) ? $config['key'] : '';
    $apiSecret = isset($config['apiSecret']) ? $config['apiSecret'] : '';
    $wordLimit = isset($config['wordLimit']) ? intval($config['wordLimit']) : 1000;
    $enablePrivateSummary = isset($config['enablePrivateSummary']) ? $config['enablePrivateSummary'] : false;
    
    if (empty($key) || empty($apiSecret)) {
        write_log("错误：key或apiSecret为空");
        return false;
    }
    
    // 限制文章内容字数
    $content = mb_substr(strip_tags($content), 0, $wordLimit, 'UTF-8');
    
    // 构建URL
    $site_url = BLOG_URL;
    $post_url = $site_url . '?post=' . $log_id;
    
    // 准备请求数据
    $data = array(
        'content' => $content,
        'title' => $title,
        'url' => $post_url,
        'key' => $key,
        'system' => 'emlog',
        'api_secret' => $apiSecret
    );
    
    write_log("准备发送请求到API，URL: {$post_url}");
    
    // 发送请求
    $ch = curl_init('https://api.ai.zhheo.com/api/v2/summary/generate/internal');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        write_log("CURL错误: " . curl_error($ch));
    }
    
    curl_close($ch);
    
    write_log("API响应状态码: {$http_code}");
    write_log("API响应内容: " . $response);
    
    if ($http_code == 200) {
        $result = json_decode($response, true);
        if ($result['code'] == 200) {
            // 只有在开启私有化摘要时才存储到数据库
            if ($enablePrivateSummary) {
                $db = MySql::getInstance();
                $prefix = DB_PREFIX;
                
                $sql = "INSERT INTO `{$prefix}postchat_summary` 
                        (log_id, summary, generate_time, is_vector) 
                        VALUES (" . intval($log_id) . ", '" . addslashes($result['data']['summary']) . "', '" . 
                        addslashes($result['data']['generate_time']) . "', '" . 
                        addslashes($result['data']['is_vector']) . "')";
                
                try {
                    $db->query($sql);
                    write_log("摘要已成功保存到数据库");
                } catch (Exception $e) {
                    write_log("数据库错误: " . $e->getMessage());
                }
            }
            return true;
        } else {
            write_log("API返回错误: " . $result['msg']);
        }
    }
    
    return false;
}

// 获取文章摘要
function get_summary($log_id) {
    write_log("尝试获取文章摘要，文章ID: {$log_id}");
    
    // 获取配置
    $db = Storage::getInstance("PostChat");
    $config = $db->getValue('config', array());
    $enablePrivateSummary = isset($config['enablePrivateSummary']) ? $config['enablePrivateSummary'] : false;
    
    // 如果开启了私有化摘要，则从数据库获取
    if ($enablePrivateSummary) {
        // 检查并创建表
        if (!check_and_create_summary_table()) {
            write_log("无法创建摘要表，返回空摘要");
            return '';
        }
        
        $db = MySql::getInstance();
        $prefix = DB_PREFIX;
        
        try {
            $sql = "SELECT summary FROM `{$prefix}postchat_summary` WHERE log_id = " . intval($log_id) . " ORDER BY id DESC LIMIT 1";
            $row = $db->once_fetch_array($sql);
            
            if ($row) {
                write_log("成功从数据库获取到摘要");
                return $row['summary'];
            } else {
                write_log("未找到摘要记录");
                return '';
            }
        } catch (Exception $e) {
            write_log("获取摘要时发生错误: " . $e->getMessage());
            return '';
        }
    }
    
    // 如果未开启私有化摘要，返回空字符串
    write_log("未开启私有化摘要，返回空摘要");
    return '';
}

// 在文章保存时生成摘要
function postchat_save_log($logid) {
    write_log("文章保存触发，文章ID: {$logid}");
    
    // 获取配置
    $db = Storage::getInstance("PostChat");
    $config = $db->getValue('config', array());
    $enableSummary = isset($config['enableSummary']) ? $config['enableSummary'] : false;
    $enablePrivateSummary = isset($config['enablePrivateSummary']) ? $config['enablePrivateSummary'] : false;
    
    // 如果未开启摘要功能，直接返回
    if (!$enableSummary) {
        write_log("摘要功能未启用，跳过生成");
        return;
    }
    
    // 如果开启了私有化摘要，检查API Secret是否已填写
    if ($enablePrivateSummary && empty($config['apiSecret'])) {
        write_log("开启了私有化摘要但未填写API Secret，跳过生成");
        return;
    }
    
    $db = MySql::getInstance();
    $sql = "SELECT title, content FROM " . DB_PREFIX . "blog WHERE gid = " . intval($logid);
    $row = $db->once_fetch_array($sql);
    
    if ($row) {
        write_log("获取到文章内容，开始生成摘要");
        generate_summary($logid, $row['title'], $row['content']);
    } else {
        write_log("未找到文章内容");
    }
}

// 注册钩子
addAction('save_log', 'postchat_save_log'); 