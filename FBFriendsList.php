<?php
class FBFriendsList
{
	const REG_EX = '/\[\"InitialChatFriendsList\",\[\],\{\"list\":(.*?)]},[0-9]+]/s';

	private $FriendsList = null;


	function __construct($FB_HTML)
	{
		if( preg_match(self::REG_EX, $FB_HTML, $matches) )
		{
			$list = json_decode($matches[0], 1);

			if( json_last_error() != JSON_ERROR_NONE )
				throw new Exception(sprintf('JSON decode error %d', json_last_error()));

			if( isset($list[2]['list']) )
				$this->FriendsList = array_unique( array_map('intval', $list[2]['list']) );
			else
				throw new Exception('Facebook list not found');
		}
		else
		{
			throw new Exception(sprintf('RegEx match error %s', self::REG_EX));
		}
	}


	function GetFriendList($Start = null, $Limit = null)
	{
		if( !is_array($this->FriendsList) )
			return null;
			
		if ( !is_null($Start) || !is_null($Limit) )
			return array_slice($this->FriendsList, (int)$Start, (int)$Limit);
			
		return $this->FriendsList;
	}
}