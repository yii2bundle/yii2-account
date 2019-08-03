<?php

namespace yii2bundle\account\domain\v3\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;

class LoginHelper {

    const DEFAULT_MASK = '+9 (999) 999-99-99';
	
	public static function getLoginByQuery(Query $query = null) {
		$query2 = Query::forge();
		$query2->where($query->getParam('where'));
		return \App::$domain->account->login->one($query2);
	}
	
	/**
	 * @param $id
	 *
	 * @return \yii2rails\domain\BaseEntity
	 *
	 * @deprecated
	 */
	public static function getLogin($id) {
		try {
			return \App::$domain->account->login->oneById($id);
		} catch(NotFoundHttpException $e) {}
		return \App::$domain->account->login->oneByLogin($id);
	}
 
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
		return $login['country_code']. $login['phone'];
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
        $result['country_code'] = '';
		$result['phone'] = $login;
		if (preg_match('/^(' . self::getPrefixExp() . ')?([+]?[\d]{1}){1}([\d]{10})$/', $login, $match)){
			$result['prefix'] = $match[1];
            $result['country_code'] = $match[2];
			$result['phone'] = $match[3];
		}
		return $result;
	}
	
	public static function validate($login)
	{
		$login = self::cleanLoginOfChar($login);
		$login = self::replaceCountryCode($login);
		return (boolean) preg_match('/^(' . self::getPrefixExp() . ')?([+]?[\d]{1}){1}([\d]{10})$/', $login);
	}
	
	protected static function cleanLoginOfChar($login)
	{
		$login = preg_replace('/[a-zа-ЯА-Я]/','',$login);
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
	
	public static function getPrefixExp()
	{
		return '[A-Z]{1,3}';
		/*$prefixList = \App::$domain->account->login->prefixList;
		usort($prefixList, 'sortByLen');
		return implode('|', $prefixList);*/
	}
	public static function formatPhoneNumber($number) {
		$cleanNumber = preg_replace('/[^\d]/', '', $number);
		return (strlen($cleanNumber) == 10) ? '7'.$cleanNumber : $cleanNumber;
	}
}
