<?php
/*

**/

class Stage extends DataObject{
	public static $db = array(
		'Title'=>'Varchar(255)',
		'Content'=>'HTMLText',
		'Order'=>'Int'
	);
	public static $has_many = array(
		'ProcessInfo'=>'ProcessInfo'
	);
	
	public static $default_sort = 'Order';


	public function PiecesOfInfo(){
		return ProcessInfo::get()->filter("StageID", $this->ID)->Count();
	}

	public function getInfoItems(){
		$results = ProcessInfo::get()
			->filter('StageID', $this->ID)
			->sort(array(
				'LinksToAnotherStageID'=>'ASC',
				'ProcessCaseID'=>'ASC',
				'Order'=>'ASC'
			));
		return $results;
	}


}