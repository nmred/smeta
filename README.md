# SWANSOFT TEAM

Master: [![Build Status](https://secure.travis-ci.org/nmred/swansoft.png?branch=master)](http://travis-ci.org/nmred/swansoft)

Everything in order to facilitate ! 一切为了方便！

## 我们的愿景

打造卓越的开源云计算监控平台！
<!-- {{{ 目录 -->

## 目录

1. **[最新特性](#最新特性)**
	* [WEB](#WEB)**
		* [WEB USER 端](#)**
		* [WEB ADMIN 端](#)**
	* [LIB](#)**
	* [运维相关](#)**
2. **[最近开发计划](#)**
	* [WEB](#)**
		* [WEB USER 端](#)**
		* [WEB ADMIN 端](#)**
	* [LIB](#)**
	* [运维相关](#)**
3. **[开发说明](#)**
	* [开发环境说明](#)**
	* [代码仓库说明](#)**
	* [开发工具库说明](#)**
		* [MAKE 工具的作用及使用说明](#)**
4. **[团队代码规范](#)**
	* [PHP 代码规范](#)**
	* [C 代码规范](#)**
	* [JAVASCRIPT 代码规范](#)**
	* [CSS/HTML5 代码规范](#)**
5. **[加入团队](#)**

<!-- }}} -->

<!-- {{{ 最新特性 -->
## 最新特性

### WEB

#### WEB USER 端

#### WEB ADMIN 端

- 增加了添加设备功能
- 增加了设备管理功能
- 增加表单提示框

### LIB

- 添加控制器模块
- 添加SNMP模块
- 添加DAEMON模块
- 修正LIB中validate对外的调用工厂

### 运维相关

- 修正开发环境中php没有mysql相关的模块的bug
- 添加开发环境中添加pcntl扩展
- 修正开发环境中缺少pdo-mysql模块的bug
 
<!-- }}} -->

<!-- {{{ 最近开发计划 -->

## 最近开发计划

### LIB

- PHPD 整体的框架
- 配合数据库表结构调整来调整对应的 ORM

### 运维相关

- 调整打包程序
- 调整数据库表结构

<!-- }}} -->

<!-- {{{ 开发说明 -->

## 开发说明

### 开发环境说明

1. SWANSOFT 的运行基础是 *inux 下运行，为了开发调试方便统一在 linux 开发。 软件发行包是类 redhat 系列的 rpm。
2. 编辑器默认使用 vim
3. 版本控制软件使用 git, 统一托管在 github

### 代码仓库说明

1. **[dev_swan]()** 是开发工具库 (存放一些开发的辅助工具)

		注意：1. 开发工具库在代码中已包含 Makefile 文件，所以可以直接执行 make install 安装工具库。
			  2. 想要正确的运行工具库中的工具必须安装最新的 rpm 软件。
			  
2. **[swansoft]()** 是 SWANSOFT 的主力开发库

### 开发工具库说明

#### MAKE 工具的作用及使用说明

1. MAKE 工具对于本项目的作用
	由于本软件的运行目录是 `/usr/local/swan/` 下， 而日常多人协助开发的时候目录一般是 `/home/xxx` 下， 借助 MAKE 工具可以将仓库中更改或新添加的代码 copy 到运行目录--目标。
2. MAKE 工具使用

	正常情况下执行的步骤：
	
		# jjcm
		# make install (将所有的文件 make 到目标)
		# make 文件名. (注意在文件名后面要紧跟一个点号, 是将单个文件 make 到目标)
		
	要使上述步骤运行成功的前提是：
	
		1) 确保当前目录中有 Makefile 文件，而初次建立目录是没有 Makefile , 建立 Makefile 需要用 jjcm 命令。
		2) jjcm 正确的运行需要的是 Makefile.ini 文件
		
3. Makefile.ini 文件配置规则
		
	a. 首先配置全局的参数[global]
	
		ignore_file :是在MAKE的时候该目录需要忽略的文件，用英文逗号分隔;
		ignore_dir  :是在MAKE的时候该目录需要忽略的子目录，用英文逗号分隔;

	b. 配置target的(#规则) ， #后面是不能重复的数字，并且#0有特殊意义，不可省略
	
	c. 关于配置每组的target字段需要注意：
		如果在文件路劲中第一个字符是(/)符号，则认为是绝对路径
	    如果没有的话就认为相对路劲，前面会连接默认的根目标路径即：/usr/local/swan/

	```php
	示例：		[target#xx]
				target = "/usr/local/swan/src/web/";   ：该组目标路劲
				target_param = "755"                   ：文件的权限
				target_user = "swan";				   ：文件属主
				target_group = "swan";				   ：文件属组
				target_dir_param = "755"               ：目标目录文件的权限
				target_dir_user = "swan";			   ：目标目录文件属主
				target_dir_group = "swan";			   ：目标目录文件属组
				src_file = "file1,file2";              ：文件列表 (*)  这个很重要，默认#0下的该配置是没有意义，
												   ：因为#0代表所有的文件，而其他的#xxx中必须包含这个配置并且用英文逗号分隔
												   ：，而且如果没有权限、属组、属主、或目标路劲不同的情况就用target#0就够用
												   ： 了。
	```	
	```php
		;/**
		;+------------------------------------------------------------------------------
		;* 生成Makefile的配置文件 
		;+------------------------------------------------------------------------------
		;* 
		;* @package 
		;* @version $_SWANBR_VERSION_$
		;* @copyright $_SWANBR_COPYRIGHT_$
		;* @author $_SWANBR_AUTHOR_$ 
		;+------------------------------------------------------------------------------
		;*/
		[global]
		ignore_file = " Makefile.ini, Makefile";
		ignore_dir = "help";

		[target#0]
		target = "/usr/local/swan/";
		target_param = "644"
		target_user = "swan";
		target_group = "swan";
		target_dir_param = "755"
		target_dir_user = "swan";
		target_dir_group = "swan";
	```

4. jjcm 工具说明
 
	本工具是 dev_swan 工具库中的工具，需要配置好工具库即可使用，本工具只能将当前目录下的文件和目录生成 Makefile ，不支持递归生成功能。
	如果想递归生成 Makefile 文件可以使用开发库目录中的 ./configure 工具生成

<!-- }}} -->
