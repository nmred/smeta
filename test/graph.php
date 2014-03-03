<?php
$outputPngFile = "/root/i_code/swan_docs/swansoft/public/speed.png";
unlink($outputPngFile);

$tmp = '/usr/local/swan/data/rrd/1_4.rrd';
$rrdFile = '/usr/local/swan/data/rrd/2_5.rrd' . time();
copy($tmp, $rrdFile);

$graphObj = new RRDGraph($outputPngFile);

	$options =
		array(
			'--color' => "SHADEA#DDDDDD",
			'--color' => "SHADEB#808080",
			'--color' => "FRAME#006600",
			'--color' => "FONT#006699",
			'--color' => "ARROW#FF0000",
			'--color' => "AXIS#000000",
			'--color' => "BACK#FFFFFF",	
			'--x-grid' => "MINUTE:12:HOUR:1:HOUR:1:0:%H",
			"-X 1 ",
  			"-t 服务器 /dev/sdb1 统计",
			"-v GB",
  			"-s " . (time() - 7200),
			"-e " . time(),
 		    "DEF:value1=$rrdFile:used:AVERAGE",
 		    "COMMENT: \\n",
 		    "COMMENT: \\n",
 		    "AREA:value1#00ff00:已使用 ",
 		    "GPRINT:value1:LAST:当前\:%.0lf",
 		    "GPRINT:value1:AVERAGE:平均\:%.0lf ",
 		    "GPRINT:value1:MAX:最大\:%.0lf",
 		    "GPRINT:value1:MIN:最小\:%.0lf",
 		    "COMMENT: \\n",
 		    "COMMENT: \\t\\t\\t\\t\\t\\t\\t最后更新 \:" . date('Y-m-d H\\\:m', time()) . "\\n",
 		    "COMMENT: \\t\\t\\t\\t\\t\\t\\tSWAN 监控数据中心\\n",
		);
	$graphObj->setOptions($options);
	$graphObj->save();
