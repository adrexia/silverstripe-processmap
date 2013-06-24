<?php
/**
* Process acts as the parent of a process structure. 
* A holder for ProcessCases 
**/

class Process extends DataObject implements PermissionProvider {
	public static $db = array(
		'Title'=>'Varchar(255)',
		'Order'=>'Int'
	);

	public static $has_many = array(
		'ProcessStages'=>'ProcessStage',
		'StopStages'=>'ProcessStopStage',
		'ProcessCases'=>'ProcessCase'
	);

	public static $searchable_fields = array(
		'Title'
	);

	public static $summary_fields = array(
		'Title'=>'Title of Process',
		'NumberOfStages'=>'Number of Stages'
	);

	public static $default_sort='Order';

	public function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->removeByName('Order');

		$fields->removeByName('Title');
		$fields->removeByName('ProcessStages');
		$fields->removeByName('StopStages');


		if($this->ID > 0){
			$stops = new GridField(
					'StopStages', 
					'Stopping Points', 
					$this->StopStages(), 
					GridFieldConfig_RelationEditor::create());

			$stopGroup = new ToggleCompositeField(
					'StopStages',
					'Stopping points in this process',
					array(
						LiteralField::create('StopDescription', '<p class="message info">Create all stops for this process. You can then link to these stops from within any stages you create.</p>'),
						$stops
					)
			);
			$stopGroup->setHeadingLevel(5);
		}else{
			$stopGroup = LiteralField::create('NoStopDescription', '<p class="message info">Save this process to add stages and stop stages</p>');
		}

		$fields->addFieldToTab('Root.Main', $processSteps = new CompositeField(
			$title = new TextField('Title','Title'),
			$stopGroup
		));

		$title->addExtraClass('process-noborder');
		$processSteps->addExtraClass('process-step');

		$fields->insertBefore(new LiteralField('StageTitle', 
			'<h3 class="process-info-header">
				<span class="step-label">
					<span class="flyout">1</span><span class="arrow"></span>
					<span class="title">Process details</span>
				</span>
			</h3>'),'Title');

		

		if($this->ID > 0){
			$fields->addFieldToTab('Root.Main', $processSteps = new CompositeField(
				new GridField(
					'ProcessStages', 
					'Process Stages', 
					$this->ProcessStages(), 
					$processStages = GridFieldConfig_RelationEditor::create())
			));
			$processStages->addComponent(new GridFieldSortableRows('Order'));
			$processSteps->addExtraClass('process-step');

			$fields->insertBefore(new LiteralField('StageTitle', 
				'<h3 class="process-info-header">
					<span class="step-label">
						<span class="flyout">2</span><span class="arrow"></span>
						<span class="title">Stages of this process</span>
					</span>
				</h3>'),'ProcessStages');
		}
		
		

		return $fields;
	}

	public function NumberOfStages(){

		if(ProcessStage::get()){

			return ProcessStage::get()->filter("ParentID", $this->ID)->Count();
		}else{
			return "none";
		}
	}

	public function NumberOfStagesWithStops(){
		if(Stage::get()){
			$stage = ProcessStage::get()->filter("ParentID", $this->ID)->Count();
			$stop = ProcessStopStage::get()->filter("ParentID", $this->ID)->Count();
			return $stage + $stop;
		}else{
			return 0;
		}
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