<?php
require_once 'core.php';

use \lib\member\sw_member;

// 添加 monitor metric
//$monitor_basic_property = sw_member::property_factory('monitor_basic', array('monitor_id' => '1'));
//$monitor_metric_property = sw_member::property_factory('monitor_metric', 
//	array('metric_name' => 'testsss1', 'collect_every' => 60, 'time_threshold' => 10));
//$monitor = sw_member::operator_factory('monitor', $monitor_basic_property);
//$metric_id = $monitor->get_operator('metric')->add_metric($monitor_metric_property);
//var_dump($metric_id);

// 获取 monitor metric
//$condition = sw_member::condition_factory('get_monitor_metric');
//$condition->set_like('monitor_name');
//$condition->set_metric_name('te');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_metric = $monitor->get_operator('metric')->get_metric($condition);
//var_dump($monitor_metric);

// 删除 monitor metric
//$condition = sw_member::condition_factory('del_monitor_metric', array('metric_id' => 1));
//$condition->set_in('metric_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_metric = $monitor->get_operator('metric')->del_metric($condition);


// 修改 monitor metric
//$monitor_property_metric = sw_member::property_factory('monitor_metric', array('time_threshold' => '30'));
//$condition = sw_member::condition_factory('mod_monitor_metric', array('metric_id' => 4));
//$condition->set_property($monitor_property_metric);
//$condition->set_in('metric_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_metric = $monitor->get_operator('metric')->mod_metric($condition);
