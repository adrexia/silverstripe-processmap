<?php
/*

**/

class ProcessInfo extends DataObject implements PermissionProvider {
	public static $db = array(
		'Title'=>'Varchar(255)',
		'Content' => 'HTMLText',
		'Order'=>'Int',
		'LinksToAnotherStageID'=>'Int'
	);

	public static $has_one = array(
		'Type'=>'TypeDefinition',
		'Service'=>'ServiceDefinition',
		'ProcessCase' =>'ProcessCase',
		'Stage' =>'Stage',
	);

	public static $searchable_fields = array(
		'Title',
		'Content',
		'Type.Name',
		'Service.Name',
		'Stage.Title',
		'ProcessCase.Title'
	);

	public static $summary_fields = array(
		'Service.Name'=>'Service',
		'Title'=>'Title',
		'ShortContent'=>'Content',
		'ProcessCaseTitle'=>'Case',
		'Type.Name'=>'Type',
		'Stage.Title'=>'Stage',
		'LinksToAnotherStageID'=>'Linked to Stage'
	);


	public static $default_sort = "Order, ProcessCaseID, LinksToAnotherStageID DESC";




	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Order');
		$fields->removeByName('Content');
		$fields->removeByName('ProcessCaseID');

		$case = ProcessCase::get();
		if ($case) {
			$fields->insertBefore($caseOptions = new DropdownField(
					'ProcessCaseID', 
					'Case', 
					$case->map('ID', 'Title')

				),'StageID');
			$caseOptions->setEmptyString('All');
		}

		$fields->insertAfter(new HiddenField("TypeOrder"), 'TypeID');

		$fields->insertAfter($content = new HTMLEditorField("Content"), 'ProcessCaseID');
		$content->setRows(15);

		$fields->insertAfter($links = new DropdownField(
					'LinksToAnotherStageID', 
					'Links To Another Stage', 
					Stage::get()->map('ID', 'Title')
		),'StageID');

		$links->setEmptyString(' ');

		$fields->insertBefore(new LiteralField('InfoTitle', 
		'<h3 class="process-info-header">
			<span class="step-label">
				<span class="flyout">5</span><span class="arrow"></span>
				<span class="title">Information Piece</span>
			</span>
		</h3>'),'Title');

		return $fields;
	}



	/*
	 * @return String
	 */
	public function ShortContent(){
		return $this->obj('Content')->Summary();
	}

	/*
	 * @return String
	 */
	public function ProcessCaseTitle(){
		if($this->ProcessCaseID){
			return $this->obj("ProcessCase")->Title;
		}else{
			return "All";
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