<?php
class ProcessDisplayPage extends Page {
	public static $icon = 'processmap/images/process.png';
	public static $description = 'A page for displaying process maps';


	public static $db = array(

	);
	public static $has_one = array(
		"Process" => "Process"
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$processes = Process::get();
		if ($processes) {
			$fields->insertBefore(
				$processesOptions = new DropdownField(
					'ProcessID', 
					'Process', 
					$processes->map('ID', 'Title')
				),'Content');
		}

		return $fields;
	}

	/*
	 * Returns the content field of the has_one Process 
	 * @return string
	*/
	public function ProcessContent(){
		return Process::get()->byID($this->ProcessID);
	}

	/* Return all cases for this process 
	*/
	public function CasesForProcess(){
		return ProcessCase::get()->filter('ParentProcessID', $this->ProcessID);
	}

	public function ProcessStages(){
		return ProcessStage::get()->filter('ParentID', $this->ProcessID);
	}

	public function ServiceDefinitions(){
		return ServiceDefinition::get();
	}

}
class ProcessDisplayPage_Controller extends Page_Controller {
	
	function init() {
		parent::init();
	}


	




}