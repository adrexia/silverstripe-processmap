<?php

class ProcessInfoExtension extends DataExtension {
	public static $api_access = true;


	public static $has_one = array(
		'Type'=>'TypeDefinition',
		'Service'=>'ServiceDefinition'
	);

	public function updateCMSFields(FieldList $fields){

		$service = ServiceDefinition::get();
		if ($service->Count() > 0) {
			$fields->insertAfter($serviceOptions = new DropdownField(
					'ServiceID', 
					'Service', 
					$service->map('ID', 'Title')

				),'Title');
			$serviceOptions->setEmptyString(' ');
		}else{
			$fields->removeByName('ServiceID');
		}

		$type = TypeDefinition::get();
		if ($type->Count() > 0) {
			$fields->insertAfter($typeOptions = new DropdownField(
					'TypeID', 
					'Type', 
					$type->map('ID', 'Title')

				),'Title');
			$typeOptions->setEmptyString(' ');
		}else{
			$fields->removeByName('TypeID');
		}
	}


}
