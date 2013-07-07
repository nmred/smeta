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

namespace lib\db\select;
use lib\db\adapter\sw_abstract;
use lib\db\sw_db_expr;
 
/**
+------------------------------------------------------------------------------
* sw_select 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_select
{
	// {{{ consts

	const DISTINCT       = 'distinct';
	const COLUMN         = 'column';
	const FROM           = 'from';
	const UNION          = 'union';
	const WHERE          = 'where';
	const GROUP          = 'group';
	const HAVING         = 'having';
	const ORDER          = 'order';
	const LIMIT_COUNT    = 'limit_count';
	const LIMIT_OFFSET   = 'limit_offset';
	const FOR_UPDATE     = 'for_update';

	const INNER_JOIN     = 'inner_join';
	const LEFT_JOIN      = 'left_join';
	const RIGHT_JOIN     = 'right_join';
	const FULL_JOIN      = 'full_join';
	const CROSS_JOIN     = 'cross_join';
	const NATURAL_JOIN   = 'natural_join';

	const SQL_WILDCARD   = '*';
	const SQL_SELECT     = 'SELECT';
	const SQL_UNION      = 'UNION';
	const SQL_UNION_ALL  = 'UNION ALL';
	const SQL_FROM       = 'FROM';
	const SQL_WHERE      = 'WHERE';
	const SQL_DISTINCT   = 'DISTINCT';
	const SQL_GROUP_BY   = 'GROUP BY';
	const SQL_ORDER_BY   = 'ORDER BY';
	const SQL_HAVING     = 'HAVING';
	const SQL_FOR_UPDATE = 'FOR UPDATE';
	const SQL_AND        = 'AND';
	const SQL_AS         = 'AS';
	const SQL_OR         = 'OR';
	const SQL_ON         = 'ON';
	const SQL_ASC        = 'ASC';
	const SQL_DESC       = 'DESC';

	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function assemble()

	public function assemble()
	{
		//todo
		return 'test todo';	
	}

	// }}}
	// }}}	
}
