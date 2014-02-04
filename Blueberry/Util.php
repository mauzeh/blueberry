<?php

class Blueberry_Util {

	/**
	 * Shortens a string if it is too long.
	 */
	public static function shortenString($string, $maxLength = 35, $keepWordsIntact = false){

        // Force __toString() on objects
        $string .= '';

        if($keepWordsIntact){
            $words = explode(' ', $string);
            $maxLength -= 3;
            foreach($words as $i => $word){
                $chars += strlen($word);
                if($chars > $maxLength) break;
                $newWords[] = $word;
            }
            if(count($words) > count($newWords)){
                return implode(' ', $newWords).'...';
            } else return $string;
        }

		if(strlen($string) > $maxLength){

			$string = substr($string, 0, $maxLength-4).'...';

		}
		return $string;

	}
}