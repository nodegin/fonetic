<?php

class Fonetic {
	
	private static $isAccent = false;
	
	/* $accent cannot starts or ends with aeiou */
	private static $accent = [
		'bl','br','by','ch','cl','cr','cy','dr','dy','fl','fr',
		'gl','gr','hy','kn','ly','my','ny','ph','pl','pr','py',
		'rh','sc','sh','sk','sl','sn','sp','sq','st','sw','sy',
		'th','tr','tw','ty','wh','wr',
	];
	
	private static $vowels = ['a','e','i','o','u'];
	
	private static $consonants = ['b','c','d','f','g','h','j','k','l','m','n','p','q','r','s','t','v','w','x','y','z'];
	
	private static function randBegin()
	{
		$begin = rand(0, 1) === 1 ? self::$accent : self::$consonants;
		return $begin[array_rand($begin)];
	}
	
	private static function randVowel()
	{
		return self::$vowels[array_rand(self::$vowels)];
	}
	
	private static function randConsonant()
	{
		return self::$consonants[array_rand(self::$consonants)];
	}
	
	private static function randLetter()
	{
		$alphabet = array_merge(self::$vowels, self::$consonants);
		shuffle($alphabet);
		return $alphabet[array_rand($alphabet)];
	}
	
	public static function generate($len)
	{
		if($len < 3) throw new Exception('Length must at least 3');
		
		$singularIsVowel = rand(0, 1) === 0;
		$text = $singularIsVowel ? self::randVowel() : self::randBegin();
		$startsLength = strlen($text);
		$startsWithAccent = $startsLength === 2;
		$len -= $startsLength;
		for($i = 1; $i <= $len; $i++)
		{
			$isPlural = ($i % 2) === 0;
			
			if($startsWithAccent) // accent returns 2 letter
			{
				$text .= self::randVowel();
				$startsWithAccent = false;
			}
			else
			{
				if($i === 1 && $singularIsVowel)
				{
					$randChar = self::randConsonant();
					if(false !== strpos('cdflmnprst', $randChar)) // like "acc", "edd", "eff", "ill"
					{
						$text .= $randChar;
						$len--;
					}
					$text .= $randChar;
				}
				else
				{
					if($singularIsVowel)
						$text .= $isPlural ? self::randVowel() : ($i === $len ? self::randLetter() : self::randConsonant());
					else
						$text .= $isPlural ? ($i === $len ? self::randLetter() : self::randConsonant()) : self::randVowel();
				}
			}
		}
		return $text;
	}
	
}
