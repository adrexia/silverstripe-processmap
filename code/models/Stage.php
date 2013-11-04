<?php
/*

**/

class Stage extends DataObject implements PermissionProvider {
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
				'Number'=>'ASC',
				'LinksToAnotherStageID'=>'ASC',
				'ProcessCaseID'=>'ASC',
				'Order'=>'ASC'
			));
		return $results;
	}

	public function providePermissions() {
		return array(
			'PROCESS_FLOW_VIEW' => array(
				'name' => 'View process map admin',
				'category' => 'Process Maps',
			),
			'PROCESS_FLOW_EDIT' => array(
				'name' => 'Edit process flows',
				'category' => 'Process Maps',
			),
			'PROCESS_FLOW_DELETE' => array(
				'name' => 'Delete from process flows',
				'category' => 'Process Maps',
			),
			'PROCESS_FLOW_CREATE' => array(
				'name' => 'Create process maps',
				'category' => 'Process Maps'
			)
		);
	}


}