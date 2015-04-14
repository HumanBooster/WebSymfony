<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HB\BlogBundle\Service;

/**
 * Description of SlugGenerator
 *
 * @author benjamin
 */
class SlugGenerator {
    //put your code here
    function getSlug($str, $replace=array(), $delimiter='-') {
	//
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);	
	$clean=strip_tags($clean);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	return $clean;
    }
}
