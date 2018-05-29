本任务为 游戏消费表的自动补单功能，代码逻辑在log.php 文件里，只适用于H53.0，其他系统暂不支持

window 打开计划任务，创建任务，把本目录下 test.bat 文件添加到计划任务里

test.bat 内容如下
	
	E:\phpStudy\php\php-5.5.38\php.exe -q E:\phpStudy\WWW\Automatic_compensation\log.php
        //替换为自己的目录

触发器可以随意设置，建议设在网站流量较少的时间段
操作 起始于填写E:\phpStudy\WWW\Automatic_compensation//替换为自己的目录，此目录是日志生成的目录






linux 定时任务

执行

crontab -e

新增

*/1 * * * * php /www/web/h5demo_vlcms_com/public_html/ThinkPHP/Library/Org/Automatic_compensation/log.php
每1分钟执行一次 自动补单

/www/web/h5demo_vlcms_com/public_html 为项目实际目录

如已安装php环境 命令仍不可用  

可执行以下命令 启动环境

PATH=$PATH:/www/wdlinux/apache_php-5.5.9/bin/:/www/wdlinux/mysql-5.1.69/bin/

export PATH  根据自己服务器实际目录配置Apache/mysql

