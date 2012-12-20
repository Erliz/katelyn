<?php
/**
 *
 */
abstract class MO_Object {
	/** @var array $args Массив параметров Класса*/
	private $args=Array();

	/**
	 * Нужно для реализации метода __get в TWIG шаблонизаторе
	 *
	 * @param $var Название параметра в $args
	 * @return bool
	 */
	public function __isset($var)
	{
		if ($this->$var!==null) return true;
		return false;
	}

	public function __get($var){
		if(array_key_exists($var,get_class_vars(get_class($this)))) return $this->{$var};
		elseif (isset($this->args[$var])) return $this->args[$var];
		else return null;
		//else throw new E_Notice('Object '.get_class($this).' has no ARGS like: '.$var.'.');
	}

	public function __set($var,$val){
		if (!isset($this->args[$var])) {
			$this->args[$var] = $val;
			return true;
		}
		else throw new E_Fatal(
            'Object '.get_class($this).' has already set ARGS with "'.$var
                .'" wanted to set '
                .(is_object($val)?get_class($val):('('.gettype($val).') "'.$val))
                .'"');
	}

	protected function __setForce($var,$val){
		$this->args[$var] = $val;
		return true;
	}

	/**
	 * Проверка наличия необходимых ключей в массиве
	 * @param $params
	 * @param $data
	 *
	 * @return array|bool
	 */
	protected function checkObjectParams($params,$data){
		$empty=array();
		foreach($params as $param){
			if(strpos($param,'.')){
				$p=explode('.',$param);
				if(!isset($data[$p[0]][$p[1]])) $empty[]=$param;
			}
			else
				if(!isset($data[$param])) $empty[]=$param;
		}
		return (empty($empty))?true:$empty;
	}
}
?>