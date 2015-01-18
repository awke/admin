<?php

function logsys($message,$priority=LOG_INFO,$facility=LOG_USER,$logid="UnKnown")
{
global $LOGGER_ID;
if(isset($LOGGER_ID))
{
$logid=$LOGGER_ID;
}
define_syslog_variables();
openlog("$logid",LOG_PID,$facility);
syslog($priority,$message);
closelog();
}

