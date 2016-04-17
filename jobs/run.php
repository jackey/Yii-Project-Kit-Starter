<?php

/**
 * 任务列表
 * 1.s -> 1s 重复执行
 * 5.s -> 5s ...
 * ...
 */

define('SUCELIT_PATH', realpath(dirname('./../../'))); // Sucel项目目录
define('BASE_PATH', realpath(dirname(__FILE__))); // 当前项目目录

define('PROJECT_NAME', 'backend_job');

define('ENV', 'prod');

require_once SUCELIT_PATH.'/common/bootstrap.php';

$options = getopt('s::c::');
$runSecond = getParam($options, 's', false);

chdir(implode(DIRECTORY_SEPARATOR, array(SUCELIT_PATH, 'jobs')));

// 程序是否退出开关
$shouldExist = false;

$jobs = array();

// 打开日志文件
$logName = date('Y-m-d').'.run.log';
$logFile = fopen('logs'.DIRECTORY_SEPARATOR.$logName, 'w+');
fseek($logFile, SEEK_END);

function logMessage($message, $type = 'message') {
    global $logFile;
    $string = date('Y-m-d H:i:s')."\t[".$type."]\t". $message."\r\n";
    fwrite($logFile, $string);
}

function destroyLog() {
    global $logFile;
    fclose($logFile);
}

function exitChildJob() {
    global $shouldExist;
    global $jobPids;
    foreach ($jobPids as $pid) {
        echo "任务 ". $pid ." 退出成功\r\n";
        posix_kill($pid, SIGTERM);
    }
    echo "任务退出成功\r\n";
    $shouldExist = true;
}

$dirIntegrator = new \DirectoryIterator(implode(DIRECTORY_SEPARATOR, array(SUCELIT_PATH, 'jobs')));
foreach ($dirIntegrator as $jobDir) {
    if (!$jobDir->isDot() && $jobDir->isDir()) {
        $name = $jobDir->getFilename();
        $second = explode('.', $name)[0];

        // 获取具体的命令文件
        $jobDirIntegrator = new \DirectoryIterator($jobDir->getRealPath());
        foreach ($jobDirIntegrator as $jobFile) {
            if (!$jobFile->isDir() && !$jobDirIntegrator->isDot()) {
                $jobFile = $jobDirIntegrator->getRealPath();
                $jobs[$second] = $jobFile;
            }
        }
    }
}

//  主程序 (因为主程序是直接运行 不需要传入  -s 选项)
if (!$runSecond) {
    $jobPids = array();
    $num = 0;
    foreach($jobs as $second => $jobFile) {
        exec('nohup php run.php -s='. $second.' &> /dev/null & echo $!', $return);
        $jobPids[] = getParam($return, $num);
        $num += 1;
    }

    // 监听 SIGNAL 收到退出信号后 退出自己和子Job
    declare(ticks = 1);
    pcntl_signal(SIGTERM, 'exitChildJob');
    pcntl_signal(SIGINT, 'exitChildJob');
    pcntl_signal(SIGTSTP, 'exitChildJob');

    // 主程序保持运行状态
    while (true) {
        if ($shouldExist) exit;
        sleep(1);
    }
}
// Job 运行程序
else {
    pcntl_signal(SIGTERM, function () use (&$shouldExist) {
        $shouldExist = true;
    });
    $oldDir = getcwd();
    while (true) {
        if ($shouldExist) exit;
        chdir($oldDir);
        $job = $jobs[$runSecond];
        logMessage('Job name'. $job, 'start run job');
        $command = file_get_contents($job);
        // 文件中命令用换行符分割
        $commands = explode("\r\n", $command);
        // 运行目录跳转到 backend 目录下
        $commandDir = SUCELIT_PATH.DIRECTORY_SEPARATOR.'backend';

        foreach ($commands as $command) {
            chdir($commandDir);
            exec('nohup '. $command.' &>/dev/null &');// 直接在后台运行
        }

        sleep($runSecond);
    }
}


