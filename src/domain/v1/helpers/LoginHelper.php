<?php

namespace yii2module\account\domain\v1\helpers;

class LoginHelper {

    const DEFAULT_MASK = '+9 (999) 999-99-99';

	public static function format($login, $mask = null)
	{
		if(!self::validate($login)) {
			return $login;
		}
		if(empty($mask)) {
			$mask = self::DEFAULT_MASK;
		}
		$result = self::formatByMask($login, $mask);
		return $result;
	}

	public static function parse($login)
	{
		$login = self::pregMatchLogin($login);
		return self::splitLogin($login);
	}
	
	// todo: покрыть тестом и раскидать там, где нужен только телефон (без префикса)
	
	public static function getPhone($login)
	{
		$login = self::pregMatchLogin($login);
		$login = self::splitLogin($login);
		return $login['phone'];
	}
	
	/**
	 * @param string $login
	 * @return string
	 */
	public static function pregMatchLogin($login)
	{
		$login = self::cleanLoginOfChar($login);
		$login = self::replaceCountryCode($login);
		return $login;
	}

	public static function splitLogin($login)
	{
		$result['prefix'] = '';
		$result['phone'] = $login;
		if (preg_match('/^(' . self::getPrefixExp() . ')([\s\S]+)$/', $login, $match)){
			$result['prefix'] = $match[1];
			$result['phone'] = $match[2];
		}
		return $result;
	}
	
	public static function validate($login)
	{
		$login = self::cleanLoginOfChar($login);
		$login = self::replaceCountryCode($login);
		return (boolean) preg_match('/^(' . self::getPrefixExp() . ')?([\d]{11})$/', $login);
	}
	
	protected static function cleanLoginOfChar($login)
	{
		$login = str_replace(['+', ' ', '-', '(', ')'], '', $login);
		return $login;
	}
	
	protected static function formatByMask($login, $mask)
	{
		$maskArray = str_split($mask, 1);
		$pos = 0;
		$result = '';
		foreach($maskArray as $char) {
			if(is_numeric($char)) {
				if($char == '9') {
					$result .= $login[$pos];
					$pos++;
				} else {
					$result .= $char;
				}
			} else {
				$result .= $char;
			}
		}
		return $result;
	}
	
	protected static function replaceCountryCode($login)
	{
		if (preg_match('/^(' . self::getPrefixExp() . ')?87([\s\S]+)$/', $login, $match)){
			$login = $match[1] . '77' . $match[2];
		}
		return $login;
	}
	
	protected static function getPrefixExp()
	{
		$prefixList = \App::$domain->account->login->prefixList;
		usort($prefixList, 'sortByLen');
		return implode('|', $prefixList);
	}
	
}
