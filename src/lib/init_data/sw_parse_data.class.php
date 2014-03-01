<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
namespace lib\init_data;
use \lib\init_data\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 初始化数据解析模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_parse_data
{
	// {{{ consts
	// }}}
    // {{{ members

    /**
     * XML 配置文件
     *
     * @var mixed
     * @access protected
     */
    protected $__xml_config = null;

    /**
     * XML 中配置的分类
     *
     * @var array
     * @access protected
     */
    protected $__categories = array(
        'devices'  => true,
        'monitors' => true,
        'metric_groups'  => true,
    );

    /**
     * 设备信息
     *
     * @var array
     * @access protected
     */
    protected $__devices = array();

    /**
     * 监控器
     *
     * @var array
     * @access protected
     */
    protected $__monitors = array();

	/**
	 * 监控数据项配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__metric_groups = array();

    // }}}
    // {{{ functions
    // {{{ public function generate_dynamic()

    /**
     * 生成动态配置
     *
     * @return void
     * @throw em_config_exception
     */
    public function generate_dynamic()
    {
		$this->_parse_config();
    }

    // }}}
	// {{{ public function get_metrics()
	
	/**
	 * 获取监控数据项 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_metrics()
	{
		return $this->__metric_groups;
	}

	// }}}
	// {{{ public function get_devices()
	
	/**
	 * 获取监控设备 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_devices()
	{
		return $this->__devices;
	}

	// }}}
	// {{{ public function get_monitors()
	
	/**
	 * 获取监控器 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_monitors()
	{
		return $this->__monitors;
	}

	// }}}
    // {{{ protected function _parse_config()

    /**
     * 解析配置文件
     *
     * @access protected
     * @return void;
     */
    protected function _parse_config()
    {
        if (null === $this->__xml_config) {
            try {
                $this->__xml_config = new \SimpleXMLIterator(PATH_INIT_DATA, 0, true);
            } catch (exception $e) {
                throw new sw_exception('parse monitor xml config file faild.');
            }
        }

        foreach ($this->__xml_config as $cate_name => $value) {
            if (!array_key_exists($cate_name, $this->__categories) || false == $this->__categories[$cate_name]) {
				continue;
//                throw new sw_exception('parse monitor config faild. category is `modules` or `collection_groups`');
            }

            $parse_func = '_parse_' . $cate_name;
            $member_config = '__' . $cate_name;
            if (empty($this->$member_config)) {
                $this->$member_config = $this->$parse_func($value);
            }
        }
    }

    // }}}
	// {{{ protected function _parse_devices()
	
	/**
	 * 解析设备 
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 */
	protected function _parse_devices($config)
	{
		$devices = array();
		foreach ($config as $device) {
			$device_attr = $device->attributes();
            if (!isset($device_attr->name)) {
                throw new sw_exception("parse xml config device error. name is empty.");
            }

            $name = (string) $device_attr->name;
            $devices[$name] = array();
            foreach ($device_attr as $attr_name => $attr_value) {
                $devices[$name][(string) $attr_name] = (string) $attr_value;
            }

			$monitors = array();
            foreach ($device->children() as $tag_name => $monitor) {
                if ('monitor' !== $tag_name) {
                    continue;
                }
				$monitor_attr = $monitor->attributes();
				$monitor_name = (string) $monitor_attr->name;
				$dm_name = (string)$monitor_attr->dm_name; 
				foreach ($monitor->children() as $mtag_name => $value) {
					$param_attr = $value->attributes();
					$param_name = (string) $param_attr->name;
					$monitors[$dm_name]['attrs'][$param_name] = (string)$value;	
				}
				$monitors[$dm_name]['name'] = $monitor_name;
				$monitors[$dm_name]['dm_name'] = (string)$monitor_attr->dm_name;
            }
			$devices[$name]['monitors'] = $monitors;
		}

		return $devices;
	}

	// }}}
	// {{{ protected function _parse_monitors()
	
	/**
	 * 解析监控器 
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 */
	protected function _parse_monitors($config)
	{
		$monitors = array();
		foreach ($config as $monitor) {
			$attributes = $monitor->attributes();
            if (!isset($attributes->name)) {
                throw new sw_exception("parse xml config monitor error. name is empty.");
            }

            $name = (string) $attributes->name;
            $monitors[$name] = array();
            foreach ($attributes as $attr_name => $attr_value) {
                $monitors[$name][(string) $attr_name] = (string) $attr_value;
            }

            $attrs = array();
            foreach ($monitor->children() as $tag_name => $tag_value) {
                if ('param' == $tag_name) {
					$param_attrs = (array)$tag_value->attributes();
					$param_attrs = $param_attrs['@attributes'];
					$param_name  = $param_attrs['name'];
					if ('' === $param_name) {
						throw new sw_exception("parse xml config monitor error. param name is empty.");
					}

					$item = array();
					foreach ($param_attrs as $attr_name => $attr_value) {
						$item[(string) $attr_name] = (string) $attr_value;
					}

					$item['attr_default'] = (string) $tag_value;
					$monitors[$name]['attrs'][] = $item;
                }

                if ('archive' == $tag_name) {
					$archive_attrs = (array)$tag_value->attributes();
					$archive_attrs = $archive_attrs['@attributes'];

					$ar_item = array();
					foreach ($archive_attrs as $attr_name => $attr_value) {
						$ar_item[(string) $attr_name] = (string) $attr_value;
					}

					$monitors[$name]['archives'][] = $ar_item;
                }
            }
		}

		return $monitors;
	}

	// }}}
	// {{{ protected function _parse_metric_groups()
	
	/**
	 * 解析监控数据项配置 
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 */
	protected function _parse_metric_groups($config)
	{
		$metric_data = array();
		foreach ($config as $metrics) {
			$group_attr = $metrics->attributes();
			$monitor_name = (string) $group_attr->module;
			$attrs = array();
			foreach ($group_attr as $attr_name => $attr_value) {
				if ('module' == (string)$attr_name) {
					continue;	
				}
				$attrs[(string) $attr_name] = (string) $attr_value;
			}

			foreach ($metrics->children() as $tag_name => $metric) {
				$metric_attr = $metric->attributes();
				$metric_name = (string) $metric_attr->name;
				foreach ($metric_attr as $attr_name => $attr_value) {
					$item[(string) $attr_name] = (string) $attr_value;
				}
				$item = array_merge($item, $attrs);
				$metric_data[$monitor_name][$metric_name] = $item; 
            }
		}

		return $metric_data;
	}

	// }}}
    // }}} end of functions
}
