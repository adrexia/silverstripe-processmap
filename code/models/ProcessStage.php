<?php
/*

**/

class ProcessStage extends Stage implements PermissionProvider {
	public static $db = array(
		'ContinueButton'=>'Varchar(255)',
		'StopButton'=>'Varchar(255)',
		'DecisionPoint'=>'Boolean'
	);

	public static $has_one = array(
		'Parent'=>'Process',
		'StopStage'=>'ProcessStopStage',
	);

	public static $many_many = array(
		'CaseFinal'=>'ProcessCase'
	);

	public static $searchable_fields = array(
		'Title',
		'Parent.Title',
		'StopStage.Title'
	);

	public static $summary_fields = array(
		'Title'=>'Title',
		'StopStageTitle'=>'Decision Stage',
		'PiecesOfInfo'=>'Pieces of Info (num)',
		'Parent.Title'=>'Process Title'
	);
	
	public static $default_sort = 'Order';

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Order');
		$fields->removeByName('ProcessInfo');
		$fields->removeByName('StopStageID');
		$fields->removeByName('StopButton');
		$fields->removeByName('ContinueButton');
		$fields->removeByName('DecisionPoint');
		$fields->removeByName('CaseFinal');

		$fields->insertBefore(new LiteralField('StageTitle', 
		'<h3 class="process-info-header">
			<span class="step-label">
				<span class="flyout">3</span><span class="arrow"></span>
				<span class="title">Stage Settings</span>
			</span>
		</h3>'),'Title');


		$caseFinalMap = ProcessCase::get()->filter(array('ParentProcessID'=>$this->ParentID))->map("ID", "Title")->toArray();
		asort($caseFinalMap);

		$case = ListboxField::create('CaseFinal', 'Final step for these Cases')
				->setMultiple(true)
				->setSource($caseFinalMap)
				->setAttribute(
					'data-placeholder', 
					_t('ProcessAdmin.Cases', 'Cases', 'Placeholder text for a dropdown'));

		$fields->insertAfter($case, 'Title');
		$fields->insertAfter(
			$group = new CompositeField(
				$label = new LabelField('switchLabel', 'Act as Decision Point'),
				new CheckboxField('DecisionPoint', '')),
			'ParentID');

		$group->addExtraClass("field special process-noborder");
		$label->addExtraClass("left");

		$fields->dataFieldByName('Content')->setRows(10);

		if($this->ID > 0){

			$fields->addFieldToTab('Root.Main', 
				new LiteralField('SaveRecord', '<p></p>'));

			$fields->addFieldToTab('Root.Main', $processInfo = new CompositeField(
				$grid = new GridField(
					'ProcessInfo', 
					'Information for this stage', 
					$this->ProcessInfo()->sort(array('TypeID'=>'ASC', 'ProcessCaseID'=>'ASC', 'LinksToAnotherStageID'=>'ASC')), 
					$gridConfig = GridFieldConfig_RelationEditor::create())
			));

			$gridConfig->addComponent(new GridFieldSortableRows('Order'));

			$processInfo->addExtraClass('process-spacing');
			$grid->addExtraClass('toggle-grid');
		}else{
			$fields->addFieldToTab('Root.Main', 
				new LiteralField('SaveRecord', '<p class="message info">Save this stage to add info</p>'));
		}

		$fields->insertBefore(new LiteralField('StageTitleInfo', 
			'<h3 class="process-info-header">
				<span class="step-label">
					<span class="flyout">4</span><span class="arrow"></span>
					<span class="title">Information</span>
				</span>
			</h3>'),'SaveRecord');

		$stopStage = ProcessStopStage::get();
		if ($stopStage) {
			$fields->insertBefore(
					$inner = new CompositeField(
						new LiteralField('Decision', 
						'<h3>Decision Point</h3>'
						),
						new LiteralField('ExplainStop', 
						'<label class="right">Choose a stop stage if you would like this stage to act as a decision point</label>'
						),
						$stop = new DropdownField(
							'StopStageID', 
							'Stop Stage', 
							$stopStage->map('ID', 'Title')
						),
						$continue = new TextField('ContinueButton', 'Button: Continue (e.g. "Yes")'),
						new TextField('StopButton','Button: Stop (e.g. "No")')

			), 'ProcessInfo');

			$stop->setEmptyString('No stop after this stage');

			$inner->addExtraClass('message special toggle-decide');
			$continue->addExtraClass('process-noborder');
			$stop->addExtraClass('process-noborder');
		}
		
		return $fields;
	}

	/*
	 * @return String
	 */
	public function StopStageTitle(){
		if($this->StopStageID){
			return $this->obj("StopStage")->Title;
		}else{
			return "No stop after this stage";
		}
	}

	public function PaddedPos($startIndex = 1) { 
		return sprintf('%02d', $startIndex); 
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