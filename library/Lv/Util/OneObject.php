<?php
class Lv_Util_OneObject
{
	protected $_id = null;
	protected $_dbc = null;

	public function __construct($parameters = null)
	{
		if(is_array($parameters))
		{
			foreach ($parameters as $key => $value)
			{
				$this->$key = $value;
			}
		}
		else if(is_int($parameters))
		{
			$class = substr(get_class($this), strrpos(get_class($this), '_') + 1);
			$dbParams[Sp] = strtolower('usp_'.$class.'_getprofile');
			$dbParams[_id] = $parameters;
			$this->_dbc = Lv_Util_DbCaller::getInstance();
			$result = $this->_dbc->call($dbParams);
				
			$this->_id = (int)$parameters;
			foreach ($result as $item)
			{
				foreach ($item as $key => $value)
				{
					$this->$key = $value;
				}
			}
		}
		else
		$this->_id = -1;
	}

	public function __set($parameter, $value)
	{
		$this->$parameter = $value;
	}

	public function __get($parameter)
	{
		return $this->$parameter;
	}

	public static function cast($object, $type)
	{
		return new $type(get_object_vars($object));
	}

	public function getSpName()
	{
		$class = substr(get_class($this), strrpos(get_class($this), '_') + 1);
		$backtrace = debug_backtrace();
		$function = $backtrace[1]['function'];
		if(substr($function,-4) == 'Item')
		return strtolower('usp_'.$class.'_profile'.'_'.$function);
		else
		return strtolower('usp_'.$class.'_'.$function);
	}

	/* 1. check $_id, $_id < 0 (new object):
	 * 1.1	get two groups of parameters: a) coreParams & profileParams
	 * 1.1.1	getting coreParams
	 * 1.1.2	getting profileParams
	 * 1.2	add coreParams
	 * 1.3	add profileParams
	 * 2. else if > 0 then (existing object):
	 * 2.1	get CoreParams
	 * 2.2	get prevProfileParams
	 * 2.3	get currProfileParams
	 * 2.4	get newProfileParams
	 * 2.5	exclude newProfileParams from currProfileParams
	 * 2.6	update CoreParams
	 * 2.7	update currProfileParams
	 * 2.8	add newProfileParams
	 */
	public function save()
	{
		$this->_dbc = Lv_Util_DbCaller::getInstance();
		$class = substr(get_class($this), strrpos(get_class($this), '_') + 1);
		$downcastedObject = self::cast($this, 'Model_'.$class);

		// 1. check $_id, $_id < 0 (new object):
		if($this->_id < 0)
		{
			// 1.1	get two groups of parameters: a) coreParams & profileParams
			$objectParams = get_object_vars($this);
			$coreParams = array();
			$profileParams = array();

			foreach ($objectParams as $key => $value)
			{
				if(substr($key, 0 , 1) == '_')
				// 1.1.1	getting coreParams
				$coreParams[$key] = $value;
				else
				// 1.1.2	getting profileParams
				$profileParams[$key] = $value;
			}
			//TODO: inclose in transactio
			//$this->_dbc->beginTransaction();

			// 1.2	add coreParams
			eval('$this->_id = $downcastedObject->add'.$class.'($coreParams);');
			// 1.3	add profileParams
			foreach ($profileParams as $key => $value)
			{
				$partialParam = array('_id' 	=> $this->_id,
									  'key'		=> $key,
									  'value' 	=> $value);
				eval('$downcastedObject->add'.$class.'ProfileItem($partialParam);');
			}
			//$this->_dbc->commitTransaction();
		}
		// 2. else if > 0 then (existing object):
		else
		{
			$objectParams = get_object_vars($this);
			$coreParams = array();
			$prevProfileParams = array();
			$currProfileParams = array();
			$newProfileParams = array();

			//$prevousObj =
			// 2.1	get CoreParams
			// 2.2	get prevProfileParams
			// 2.3	get currProfileParams
			// 2.4	get newProfileParams
			// 2.5	exclude newProfileParams from currProfileParams
			// 2.6	update CoreParams
			// 2.7	update currProfileParams
			// 2.8	add newProfileParams
		}


			
	}

	public function getProfile()
	{
		if ($this->_id > 0)
		{
			return new self($this->_id);
		}
	}

}