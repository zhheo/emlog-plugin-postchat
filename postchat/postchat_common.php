<?php
// 防止非法访问
!defined('EMLOG_ROOT') && exit('access denied!');

// 日志记录函数
function write_log($message) {
    // 临时禁用日志记录
    return;
    // $log_file = '/www/wwwroot/emlog.zhheo.com/postchat.log';
    // $timestamp = date('Y-m-d H:i:s');
    // $log_message = "[{$timestamp}] {$message}\n";
    // file_put_contents($log_file, $log_message, FILE_APPEND);
}

// 检查并创建摘要表
function check_and_create_summary_table() {
    $db = MySql::getInstance();
    $prefix = DB_PREFIX;
    
    // 检查表是否存在
    $sql = "SHOW TABLES LIKE '{$prefix}postchat_summary'";
    $result = $db->query($sql);
    
    if ($db->num_rows($result) == 0) {
        write_log("摘要表不存在，开始创建");
        // 创建摘要表
        $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}postchat_summary` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `log_id` int(11) NOT NULL COMMENT '文章ID',
            `summary` text NOT NULL COMMENT '摘要内容',
            `generate_time` datetime NOT NULL COMMENT '生成时间',
            `is_vector` varchar(20) NOT NULL DEFAULT 'generating' COMMENT '向量状态',
            PRIMARY KEY (`id`),
            KEY `log_id` (`log_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        try {
            $db->query($sql);
            write_log("摘要表创建成功");
        } catch (Exception $e) {
            write_log("创建摘要表失败: " . $e->getMessage());
            return false;
        }
    }
    return true;
}
?> 