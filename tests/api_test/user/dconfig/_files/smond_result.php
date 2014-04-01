<?php
return array (
  '1_1' => 
  array (
    'params' => 
    array (
      'url' => 'http://www.apache_web1.com/server_status',
      'url_a' => 'http://www.apache_web1.com/server_status',
    ),
    'basic' => 
    array (
      'monitor_id' => '1',
      'monitor_name' => 'apache_web1',
      'device_id' => '1',
      'madapter_id' => '1',
      'madapter_name' => 'apache',
      'store_type' => '2',
      'madapter_type' => '2',
      'madapter_display_name' => 'apache监控适配器',
      'host_name' => '192.168.1.114',
      'device_name' => 'lan-114',
    ),
    'metrics' => 
    array (
      0 => 
      array (
        'metric_id' => '1',
        'metric_name' => 'ap_busy_workers',
        'madapter_id' => '1',
        'collect_every' => '30',
        'time_threshold' => '2000',
      ),
      1 => 
      array (
        'metric_id' => '2',
        'metric_name' => 'ap_idle_workers',
        'madapter_id' => '1',
        'collect_every' => '30',
        'time_threshold' => '2000',
      ),
    ),
  ),
  '1_2' => 
  array (
    'params' => 
    array (
      'url' => 'http://www.apache_web2.com/server_status',
      'url_a' => 'http://www.apache_web2.com/server_status',
    ),
    'basic' => 
    array (
      'monitor_id' => '2',
      'monitor_name' => 'apache_web2',
      'device_id' => '1',
      'madapter_id' => '1',
      'madapter_name' => 'apache',
      'store_type' => '2',
      'madapter_type' => '2',
      'madapter_display_name' => 'apache监控适配器',
      'host_name' => '192.168.1.114',
      'device_name' => 'lan-114',
    ),
    'metrics' => 
    array (
      0 => 
      array (
        'metric_id' => '1',
        'metric_name' => 'ap_busy_workers',
        'madapter_id' => '1',
        'collect_every' => '30',
        'time_threshold' => '2000',
      ),
      1 => 
      array (
        'metric_id' => '2',
        'metric_name' => 'ap_idle_workers',
        'madapter_id' => '1',
        'collect_every' => '30',
        'time_threshold' => '2000',
      ),
    ),
  ),
);
