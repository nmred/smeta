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
 
/**
+------------------------------------------------------------------------------
* controller request 抽象类
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_request_abstract
{
    // {{{  members
    
    /**
     * 方法是否已经被分配 
     * 
     * @var boolean
     * @access protected
     */
    protected $__dispatched = false;

    /**
     * 模版 
     * 
     * @var string
     * @access protected
     */
    protected $__module;

    /**
     * 通过参数取得模版的key值 
     * 
     * @var string
     * @access protected
     */
    protected $__module_key = 'module';

    /**
     * 控制器名称 
     * 
     * @var string
     * @access protected
     */
    protected $__controller;

    /**
     * 通过参数获得的控制器key值 
     * 
     * @var string
     * @access protected
     */
    protected $__controller_key = 'controller';

    /**
     * 方法名字 
     * 
     * @var string
     * @access protected
     */
    protected $__action;

    /**
     * 通过参数获得的方法key值 
     * 
     * @var string
     * @access protected
     */
    protected $__action_key = 'action';

    /**
     * 请求的参数 
     * 
     * @var array
     * @access protected
     */
    protected $__params = array();

    //  }}} members
    // {{{ functions
    // {{{ public function get_module_name()

    /**
     * 获取模版名称 
     * 
     * @access public
     * @return string
     */
    public function get_module_name()
    {
        if (null === $this->__module) {
            $this->__module = $this->get_param($this->get_module_key());
        }

        return $this->__module;
    }

    // }}}
    // {{{ public function set_module_name()

    /**
     * 设置模版值 
     * 
     * @param string $value 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_module_name($value)
    {
        $this->__module = $value;

        return $this;
    }

    // }}}
    // {{{ public function get_controller_name()

    /**
     * 获取控制器名称 
     * 
     * @access public
     * @return string
     */
    public function get_controller_name()
    {
        if (null === $this->__controller) {
            $this->__controller = $this->get_param($this->get_controller_key());
        }

        return $this->__controller;
    }

    // }}}
    // {{{ public function set_controller_name()
    
    /**
     * 设置使用的控制器 
     * 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_controller_name()
    {
        $this->__controller = $value;

        return $this;
    }

    // }}} 
    // {{{ public function get_action_name()

    /**
     * 获取方法名 
     * 
     * @access public
     * @return string
     */
    public function get_action_name()
    {
        if (null === $this->__action) {
            $this->__action = $this->get_param($this->get_action_key());
        }

        return $this->__action;
    }

    // }}}
    // {{{ public function set_action_name()
        
    /**
     * 设置方法名 
     * 
     * @param string $value 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_action_name($value)
    {
        $this->__action = $value;
        if (null == $value) {
            $this->set_param($this->get_action_key(), $value);
        }

        return $this;
    }

    // }}}
    // {{{ public function get_module_key()

    /**
     * 获取模版方法名 
     * 
     * @access public
     * @return string
     */
    public function get_module_key()
    {
        return $this->__module_key;
    }

    // }}}
    // {{{ public function set_module_key()

    /**
     * 设置模版键值 
     * 
     * @param string $key 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_module_key($key)
    {
        $this->__module_key = (string) $key;
        return $this;
    }

    // }}}
    // {{{ public function get_controller_key()

    /**
     * 获取控制器键值 
     * 
     * @access public
     * @return string
     */
    public function get_controller_key()
    {
        return $this->__controller_key;
    }

    // }}}
    // {{{ public function set_controller_key()

    /**
     * 设置控制器键值 
     * 
     * @param string $key 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_controller_key($key)
    {
        $this->__controller_key = (string) $key;
        return $this;
    }

    // }}}
    // {{{ public function get_action_key()
    
    /**
     * 获取方法的键值 
     * 
     * @access public
     * @return string
     */
    public function get_action_key()
    {
        return $this->__action_key;
    }

    // }}}
    // {{{ public function set_action_key()

    /**
     * 设置方法的键值 
     * 
     * @param string $key 
     * @access public
     * @return object em_controller_request_abstract
     */
    public function set_action_key($key)
    {
        $this->__action_key = (string) $key;
        return $this;
    }

    // }}}
    // {{{ public function get_param()
    
    /**
     * 获取一个方法的参数 
     * 
     * @param string $key 
     * @param mixed $default 默认值，如果找不到对应的key 
     * @access public
     * @return mixed
     */
    public function get_param($key, $default = null)
    {
        $key = (string) $key;
        if (isset($this->__params[$key])) {
            return $this->__params[$key];
        }

        return $defalut;
    }

    // }}}
    // {{{ public function get_user_params()

    /**
     * 获取user级别的参数  
     * 
     * @access public
     * @return array
     */
    public function get_user_params()
    {
        return $this->__params;
    }

    // }}}
    // {{{ public fucntion get_user_param()

    /**
     * 获取特定user获取单个参数 
     * 
     * @param string $key 
     * @access public
     * @return mixed
     */
    public function get_user_param($key, $default = null)
    {
        if (isset($this->__params[$key])) {
            return $this->__params[$key];
        }

        return $default;
    }

    // }}}
    // {{{ public function get_params()
    
    /**
     * 获取所有参数 
     * 
     * @access public
     * @return array
     */
    public function get_params()
    {
        $this->__params;    
    }

    // }}}
    // {{{ public function set_params()

    /**
     * 设置请求参数，对其进行覆盖
     * 如果设置为null的参数将unset掉
     * 
     * @param array $array 
     * @access public
     * @return void
     */
    public function set_params(array $array) 
    {
        $this->__params = $this->__params + (array)$array;
        foreach ($this->__params as $key => $value) {
            if (null == $value) {
                unset($this->__params[$key]);
            }
        }

        return $this;
    }

    // }}}
    // {{{ public function set_dispatched()

    /**
     * 设置该请求已经被分配 
     * 
     * @param boolean $flag 
     * @access public
     * @return object sw_controller_request_abstract
     */
    public function set_dispatched($flag = true)
    {
        $this->__dispatched = $flag ? true : false;
        return $this;
    }

    // }}}
    // {{{ public function is_dispatched()

    /**
     * 判断该request是否已经被分配 
     * 
     * @access public
     * @return boolean
     */
    public function is_dispatched()
    {
        return $this->__dispatched;
    }

    // }}}
    // }}}
}
