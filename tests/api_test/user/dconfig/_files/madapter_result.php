<?php
return array (
  1 => 
  array (
    'archives' => 
    array (
      0 => 
      array (
        'archive_id' => '1',
        'madapter_id' => '1',
        'title' => '2day',
        'cf_type' => '1',
        'xff' => '0.5',
        'steps' => '1',
        'rows' => '600',
      ),
      1 => 
      array (
        'archive_id' => '2',
        'madapter_id' => '1',
        'title' => '2week',
        'cf_type' => '1',
        'xff' => '0.5',
        'steps' => '6',
        'rows' => '700',
      ),
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
        'tmax' => '600',
        'dst_type' => '1',
        'vmin' => 'U',
        'vmax' => 'U',
        'unit' => 'N',
        'title' => 'Apache - 繁忙线程数',
      ),
      1 => 
      array (
        'metric_id' => '2',
        'metric_name' => 'ap_idle_workers',
        'madapter_id' => '1',
        'collect_every' => '30',
        'time_threshold' => '2000',
        'tmax' => '600',
        'dst_type' => '1',
        'vmin' => 'U',
        'vmax' => 'U',
        'unit' => 'N',
        'title' => 'Apache - 空闲线程数',
      ),
    ),
    'basic' => 
    array (
      'madapter_id' => '1',
      'madapter_name' => 'apache',
      'madapter_display_name' => 'apache监控适配器',
      'steps' => '300',
      'store_type' => '2',
      'madapter_type' => '2',
    ),
  ),
  2 => 
  array (
    'archives' => 
    array (
    ),
    'metrics' => 
    array (
    ),
    'basic' => 
    array (
      'madapter_id' => '2',
      'madapter_name' => 'mysql',
      'madapter_display_name' => 'mysql监控适配器',
      'steps' => '300',
      'store_type' => '2',
      'madapter_type' => '2',
    ),
  ),
);
