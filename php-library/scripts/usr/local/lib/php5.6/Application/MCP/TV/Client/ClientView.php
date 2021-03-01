<?php
/**
 * taxEngineClient_ClientView.
 *
 * A simple HTTP client user interface to display the input tax form
 * and display the output from the taxEngine.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2009. RushTax, Inc.
 * @license http://opensource.org/licenses/lgpl-3.0.html
 * @package taxEngine
 * @subpackage taxEngineClient
 */
/*
 	taxEngineClient_ClientView is copyright � 2008, 2009. RushTax, Inc.

	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 3 of the License, or (at your option) any later version.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public
	License along with this library; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/**
 * taxEngineClient_ClientView.
 *
 * A simple HTTP client user interface to display the input tax form
 * and display the output from the taxEngine.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2009. RushTax, Inc.
 * @license http://opensource.org/licenses/lgpl-3.0.html
 * @package taxEngine
 * @subpackage taxEngineClient
 */
class taxEngineClient_ClientView
{
	/**
	 * The callback url for the form.
	 * @var string
	 */
	private $formURL;

	/**
	 * inputForm. String containing display copy of the input data.
	 * @var string
	 */
	private $inputForm;
	
	/**
	 * String containing display copy of the taxEngine output data.
	 * @var unknown_type
	 */
	private $taxEngineBuffer;

	/**
	 * Tax year.
	 * @var string|integer
	 */
	private $defaultYear;

	/**
	 * Array of tax years which may be processed.
	 * @var array[string]mixed
	 */
	private $validYears			= array();
	
	/**
	 * Array of filing statuses.
	 * @var array[string]integer
	 */
	private $filingStatus		= array();

	/**
	 * Names of input fields
	 * @var array[string]string
	 */
	private $fieldNames			= array();

	/**
	 * Transalte names of input fields to output strings
	 * @var array[string]string
	 */
	private $fieldTranslate		= array();
	
	/**
	 * Array to contain the result
	 * @var array[string]string
	 */
	private $resultFields		= array();

    /**
     * class constructor.
     *
     * Create a new class object.
     * @param string formURL (optional) contains url of the tax input form (assigned to Submit button)
     * @return reference to new object
     */
	public function __construct($formURL=null)
	{
	  	$this->formURL = $formURL;

	  	$this->inputForm = '';
	    $this->taxEngineBuffer = '';

	    $this->fieldNames = array('tax_year',
								  'filing_status',
								  'itin',
	    
  	  							  'tp_age',
  	  							  'self_wages',
								  'deduct_tp_fedl_inctaxtodate',
								  'tp_se_income',
								  'tp_se_expense',

								  'sp_age',
  	  							  'spouse_wages',
								  'deduct_sp_fedl_inctaxtodate',
								  'sp_se_income',
								  'sp_se_expense',

								  'exempt_dependents',
								  'exempt_disabledChild',
								  'exempt_under14to16',
								  'exempt_student19to24',
								  'other_dependents',
								  'exempt_under17',
								  'exempt_itin',
	  
								  'interest_inc',
								  'ordinary_dividend',
								  'qualifying_dividend',
								  'sl_refund',
								  'held_oneyr_orless',
								  'held_gt_oneyr',
								  'soc_sec_ben',
								  'other_inc_loss',

								  'deduct_real_estate',
								  'deduct_charity',
								  'deduct_sl_tax',
								  'deduct_med',

								  'deduct_deduct_job_tax',
								  'deduct_other_credits',
								  'deduct_deduct_int',
								  'deduct_invest_int',

	  							  'deduct_tuition',
								  'deduct_tuition_hope',
	  							  'deduct_tuition_lifetime',
	
								  'taxcalc_depcare',

	    						  'student_loan_int',
								  'other_adjto_inc',
								  'taxpmt_est_taxpaid',
	  							  'save_label',
	  							  'description',
  	  							  );
  	  							
	    $this->fieldTranslate 
	                  = array('tax_year'                    => 'Tax year',
	    					  'filing_status'               => 'Filing status',
	                          'itin'                        => 'ITIN',

	                          'tp_age'                      => 'Age',
	    					  'self_wages'                  => 'Wages',
	    					  'deduct_tp_fedl_inctaxtodate'	=> 'Income taxes withheld',
							  'tp_se_income'                => 'Self-employed income',
							  'tp_se_expense'				=> 'Self-employed expenses',

	                          'sp_age'                      => 'Age',
	    					  'spouse_wages'                => 'Wages',
	    					  'deduct_sp_fedl_inctaxtodate'	=> 'Income taxes withheld',
							  'sp_se_income'                => 'Self-employed income',
	    					  'sp_se_expense'				=> 'Self-employed expenses',

	    					  'exempt_dependents'			=> 'Dependents',
	    					  'exempt_disabledChild'		=> 'Disabled children',
	    					  'exempt_under14to16'			=> 'Children age 14 to 16',
	    					  'exempt_student19to24'		=> 'Student 19 to 24',
	                          'other_dependents'			=> 'Other dependents',
	                          'exempt_under17'              => 'Under 17',
	    					  'exempt_itin'					=> 'Exempt ITIN',
	                          
	    					  'interest_inc'				=> 'Income from interest',
	    					  'ordinary_dividend'			=> 'Ordinary dividend',
							  'qualifying_dividend'			=> 'Qualifying dividend',
							  'sl_refund'					=> 'State and local tax refund',
							  'held_oneyr_orless'			=> 'Held one year or less',
							  'held_gt_oneyr'				=> 'Held greater than 1 year',
							  'soc_sec_ben'					=> 'Social Security Benefit',
							  'other_inc_loss'				=> 'Other income or loss',

							  'deduct_real_estate'			=> 'Real estate and mortgage',
							  'deduct_charity'				=> 'Charity',
							  'deduct_med'					=> 'Medical',
	                          'deduct_sl_tax'				=> 'State and local taxes',
							  'deduct_deduct_job_tax'		=> 'Job expenses and tax prep. fees',
							  'deduct_other_credits'		=> 'Other itemized decuctions',

							  'deduct_deduct_int'			=> 'Deductable interest',
							  'deduct_invest_int'			=> 'Deductable investment interest',
	  						  'deduct_tuition'				=> 'Tuition',
							  'deduct_tuition_hope'			=> 'Tuition - Hope',
	  						  'deduct_tuition_lifetime'		=> 'Tuition - Lifetime',
							  'student_loan_int'			=> 'Student loan interest',

							  'taxcalc_depcare'				=> 'Dependent care',
							  'other_adjto_inc'				=> 'Other adjustments to income',
							  'taxpmt_est_taxpaid'			=> 'Estimated taxes paid'
  	  						  );
	    
	    $this->resultFields
  		                    = array('agi'                  => 'Adjusted Gross Income',
  									'st_deduction'         => 'Itemized / Standard Deduction',
  									'pers_exemptions'      => 'Personal Exemptions',
  									'taxable_income'       => 'Taxable Income',
  									'reg_tax'              => 'Regular Tax',
  									'amt_par1'             => '(AMT Part 1)',
  								    'amt_par2'             => '(AMT Part 2)',
  									'amt'                  => 'Alternative Minimum Tax',
  		                            'child_care_credit'    => 'Child Care Credit',
  									'lifetime_lcredit'     => 'Hope/Lifetime learning credit',
  		                            'child_tax_credit'     => 'Child tax credit',
  									'other_credits'        => 'Other Credits',
  									'tax_after_credits'    => 'Tax After Credits',
  									'self_empl_tax'        => 'Self-employment tax',
  									'total_tax'            => 'Total tax',
  									'eic'                  => 'Earned Income credit',
  									'add_child_tax_credit' => 'Additional child tax credit',
  									'fed_inheld'           => 'Federal Income Taxes Withheld',
  									'estimated_taxes'      => 'Estimated Tax Payments',
  									'refund'               => 'Refund',
  		                    		'owe'				   => 'Payment Due',
  									'payments'             => 'Total Payments',
  									'penalty'			   => 'Penalty',
  									'refund_penalty'  	   => 'Refund + Penalty'
  		                            );

  		  $this->validYears = taxData_FederalTaxes_TaxYears::$validYears;
  		  $this->defaultYear = taxData_FederalTaxes_TaxYears::DEFAULT_YEAR;
  		  $this->filingStatus = array('Single'			   => taxData_FederalTaxes_TaxYears::FILING_SINGLE,
  									  'Married - Joint'    => taxData_FederalTaxes_TaxYears::FILING_MARRIED_JOINTLY,
  									  'Married - Separate' => taxData_FederalTaxes_TaxYears::FILING_MARRIED_SEPARATELY,
  									  'Head of Household'  => taxData_FederalTaxes_TaxYears::FILING_HEAD_OF_HOUSEHOLD,
  									  'Qualified Widow'	   => taxData_FederalTaxes_TaxYears::FILING_QUALIFYING_WIDOW);
	}

    /**
     * class destructor.
  	 *
     * Destroy the class object.
     * @return null
     */
	public function __destruct()
	{
	}

    /**
     * formatResult.
     *
     * format the result string for HTML output.
     * @param string resultString result from the taxengine to be formatted
     * @return string buffer contains formatted result
     */
	public function formatResult($resultString, $resultName)
	{
		$result = explode('&', $resultString);
		if (count($result) == 0)
		{
		    return sprintf('No results returned: %s', $resultString);
		}

		$buffer = '';

	    foreach($result as $computeResult)
		{
			if (! $computeResult)
			{
				continue;
			}
			
		    list($name, $value) = split('=', $computeResult);
		    $resultFields[$name] = $value;
		}

		$refund = (integer)$resultFields['refund'];
		if ($refund <= 0)
		{
			$refundColor = 'clsColorSchemeResultRefund';
			$refundType = 'Tax Refund';
			$refund = abs($refund);
			$font = 'clsFontSchemeRefund';
		}
		else
		{
			$refundColor = 'clsColorSchemeResultOwe';
			$refundType = 'Payment Due';
			$refund = -1 * $refund;
			$font = 'clsFontSchemeOwe';
		}

		$resultFields['refund'] = $refund;

		$this->appendBufferLine($buffer, sprintf('<table class="clsTableResult %s %s" style="vertical-align:top;">',
		                                         $font,
		                                         $refundColor));

		  $this->appendBufferLine($buffer, '<tr class="clsTR">');

		    $this->appendBufferLine($buffer, sprintf('<td class="clsRefundFontStdBld clsRefundField %s">',
		                                             $font));
	  	    $this->appendBufferLine($buffer, sprintf('%s:&nbsp;$%s</td>', 
	  	                                             $refundType,
	  	                                             abs($refund)));

		  $this->appendBufferLine($buffer, '</tr>');

		$this->appendBufferLine($buffer, '</table>');
		
		$this->appendBufferLine($buffer, '<br><div style="width:100%;text-align:center;">');
		$this->appendBufferLine($buffer, sprintf('<form method="POST" action="%s">', $this->formURL));
	  	    $this->appendBufferLine($buffer, '<input class="clsFontSchemeAltBld" type="submit" value="New Form" name="clear">');
		$this->appendBufferLine($buffer, '</form>');
		$this->appendBufferLine($buffer, '</div>');
		
		$this->appendBufferLine($buffer, sprintf('<table class="clsTableResult %s clsFontSchemeStd" style="vertical-align:top;">',
		                                         $refundColor));

		  $this->appendBufferLine($buffer, '<tr class="clsTR clsFontSchemeStd">');
		    $this->appendBufferLine($buffer, sprintf('<td colspan="2" class="clsTableBlockTitle clsFontSchemeStdBld">%s</td>',
		                                             $refundType));
		  $this->appendBufferLine($buffer, '</tr>');

		  $this->appendBufferLine($buffer, '<tr class="clsTR clsFontSchemeStd">');
		    $this->appendBufferLine($buffer, '<td colspan="2">&nbsp;</td>');
	      $this->appendBufferLine($buffer, '</tr>');

	      foreach($resultFields as $name => $value)
		  {
			if (! $name)
			{
				continue;
			}

			$font = 'clsFontSchemeStd';
			if ($name == 'refund')
			{
			    if ($value < 0)
				{
			    	$name = 'owe';
					$value = abs($value);
					$font = 'clsFontSchemeOwe';
				}
				else
				{
					$font = 'clsFontSchemeRefund';
				}
			}

		    $this->appendBufferLine($buffer, '<tr class="clsTR clsFontSchemeStd">');

		      $this->appendBufferLine($buffer, sprintf('<td class="clsTableDataField %s">%s:</td>', 
		                                               $font,
		                                               $this->resultFields[$name]));
		      $this->appendBufferLine($buffer, sprintf('<td class="clsTableDataValue %s">%s</td>',
		                                               $font,
		                                               $value));

		    $this->appendBufferLine($buffer, '</tr>');
		  }

		  $this->appendBufferLine($buffer, '<tr class="clsTR clsFontSchemeStd">');
		    $this->appendBufferLine($buffer, '<td colspan="2">&nbsp;</td>');
	      $this->appendBufferLine($buffer, '</tr>');

		$this->appendBufferLine($buffer, '</table>');

        return $buffer;
	}

    /**
     * formatTaxEngineForm.
     *
     * Format the result string for output.
     * @param string resultString result returned from the taxEngine soap server
     * @return reference to new object
     */
	public function formatTaxEngineForm($resultString)
	{
		$this->taxEngineBuffer = $this->formatResult($resultString, "taxEngine");
        return $this->taxEngineBuffer;
	}

	/**
	 * taxpayerInputForm.
	 *
	 * Create the taxpayer input form.
	 * @param array[string]string $inputRequest is the array containing the input request form, or empty for blank form.
	 * @return string $inputForm is the input form
	 */
	public function taxpayerInputForm($inputRequest=array())
	{
	  	if (count($inputRequest) == 0)
	  	{
	  		foreach($this->fieldNames as $name)
	  		{
	  			if (($name == 'save_label') ||
	  			    ($name == 'description'))
	  			{
	  				$inputRequest[$name] = '';
	  			}
	  			else
	  			{
	  				$inputRequest[$name] = 0;
	  			}
	  		}

	  		$inputRequest['tax_year'] = taxData_FederalTaxes_TaxYears::DEFAULT_YEAR;
	  		$inputRequest['filing_status'] = taxData_FederalTaxes_TaxYears::FILING_MARRIED_JOINTLY;
	  	}

		$this->inputForm = '';

		$this->appendBufferLine($this->inputForm, sprintf('<form method="POST" action="%s">', $this->formURL));
		  $this->appendBufferLine($this->inputForm, $this->tablePersonalInfo($inputRequest));
		$this->appendBufferLine($this->inputForm, '</form>');

		return $this->inputForm;
	}

	/**
	 * tablePersonalInfo.
	 *
	 * Create personal and earnings information table for output.
	 * @param array[string]string $inputRequest is the array containing the request fields. 
	 * @return string $tableForm is the formatted table data.
	 */
	public function tablePersonalInfo(&$inputRequest)
	{
		$tableForm = '';
	 	$this->appendBufferLine($tableForm, '<table class="clsTable clsColorSchemeTable clsFontSchemeStd">');

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4" class="clsTableBlockTitle">');
	  	      $this->appendBufferLine($tableForm, 'Taxpayer Data');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	 	  //
	  	  //		Tax Year and Filing Status
		  //

		  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataField clsFontSchemeStd">');
			  $this->appendBufferLine($tableForm, 'Tax year ');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataValue clsFontSchemeStd">');
				$value = $inputRequest['tax_year'];
				if ((! $value) ||
				    (! is_numeric($value)))
				{
					$value = taxData_FederalTaxes_TaxYears::DEFAULT_YEAR;
				}

	  	        $this->appendBufferLine($tableForm, '<select size="1" class="clsSelectData clsColorSchemeWtBlue clsFontSchemeStd" name="tax_year">');
	  			foreach(taxData_FederalTaxes_TaxYears::$validYears as $index => $year)
	  			{
	  			  	$this->appendBuffer($tableForm, sprintf('<option value="%s"', $year));
	  			  	if ($value == $year)
	  			  	{
	  			  		$this->appendBuffer($tableForm, ' selected');
	  			  	}
	  			  	$this->appendBufferLine($tableForm, sprintf('>%s&nbsp;</option>', $year));
	  			}
	  		  	$this->appendBufferLine($tableForm, '</select>');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataField clsFontSchemeStd">');
			  $this->appendBufferLine($tableForm, 'Filing status ');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataValue clsFontSchemeStd">');
	  			$this->appendBufferLine($tableForm, '<select size="1" class="clsSelectData clsColorSchemeWtBlue clsFontSchemeStd" name="filing_status">');
				$value = $inputRequest['filing_status'];
	  			foreach(taxData_FederalTaxes_TaxYears::$filingStatusNames as $filingStatus => $statusName)
	  			{
	  			  	$this->appendBuffer($tableForm, sprintf('<option value="%s"', $filingStatus));
	  			  	if ($value == $filingStatus)
	  			  	{
	  			  		$this->appendBuffer($tableForm, ' selected');
	  			  	}
	  			  	$this->appendBufferLine($tableForm, sprintf('>%s&nbsp;</option>', $statusName));
	  			}
	  		  	$this->appendBufferLine($tableForm, '</select>');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	"Taxpayer Information" and "Spouse Information" Block Titles
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Taxpayer Information');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Spouse Information');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //		Taxpayer and spouse information
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->earningsInfo($inputRequest, array('tp_age',
	  	                                                                                   'self_wages',
	  	                                                                                   'deduct_tp_fedl_inctaxtodate',
	  	                                                                                   'tp_se_income', 
	  	                                                                                   'tp_se_expense', 
	  	                                                                                   'itin')));
	 		$this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->earningsInfo($inputRequest, array('sp_age',
	  	                                                                                   'spouse_wages',
	  	                                                                                   'deduct_sp_fedl_inctaxtodate',
	  	                                                                                   'sp_se_income', 
	  	                                                                                   'sp_se_expense', 
	  	                                                                                   ' ')));
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	"Dependent" Block Title
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Dependent Information');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //		Taxpayer and spouse information
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputFieldInfo($inputRequest, array('exempt_dependents',
	  	                                                                                    'exempt_disabledChild',
	  	                                                                                    'other_dependents',
	  	                                                                                    'exempt_itin')));
	 		$this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputFieldInfo($inputRequest, array('exempt_under14to16',
	  	                                                                                    'exempt_student19to24',
	  	                                                                                    'exempt_under17',
	  	                                                                                    ' ')));
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	"Other Income" Block Title
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Income From Other Sources');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //		Other income
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputBGColorFieldInfo($inputRequest, array('interest_inc',
	  	                                                                                 'held_oneyr_orless',
	  	                                                                                 'held_gt_oneyr',
	  	                                                                                 'soc_sec_ben')));
	 		$this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputBGColorFieldInfo($inputRequest, array('ordinary_dividend',
	  	                                                                                 'qualifying_dividend',
	  	                                                                                 'sl_refund',
	  	                                                                                 'other_inc_loss')));
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	  	  
	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	"Itemized Deductions" Block Title
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Itemized Deductions');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //		Itemized deductions
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputFieldInfo($inputRequest, array('deduct_real_estate',
	  	                                                                                     'deduct_charity',
	  	                                                                                     'deduct_med')));
	 		$this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputFieldInfo($inputRequest, array('deduct_sl_tax',
	  	                                                                                     'deduct_deduct_job_tax',
	  	                                                                                     'deduct_other_credits')));
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	  	  
	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	"Additional Deductions" Block Title
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4" class="clsTableBlockTitle clsFontSchemeStdBld">');
			  $this->appendBufferLine($tableForm, 'Additional Deductions');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //		Itemized deductions
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputBGColorFieldInfo($inputRequest, array('deduct_tuition',
	  	                                                                                            'deduct_tuition_hope',
	  	                                                                                            'deduct_tuition_lifetime',
	  	                                                                                            'student_loan_int',
	  	                                                                                            'taxcalc_depcare')));
	 		$this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td colspan="2" width="50%" class="clsTableDataField clsFontSchemeStd">');
	  	      $this->appendBufferLine($tableForm, $this->inputBGColorFieldInfo($inputRequest, array('other_adjto_inc',
	  	                                                                                            'deduct_deduct_int',
	  	                                                                                            'deduct_invest_int',
	  	                                                                                            ' ',
	  	                                                                                            'taxpmt_est_taxpaid')));
	  	      $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	  	  
	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	Submit buttons
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td style="width:50%;" colspan="2">');
	  	      $this->appendBuffer($tableForm, '<input class="clsFontSchemeAltBld" type="submit" value="Calculate Taxes" name="submit">');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td style="width:50%;" colspan="2">');
	  	      $this->appendBufferLine($tableForm, '<input class="clsFontSchemeAltBld" type="submit" value="Clear" name="clear">');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //	Blank Row
	  	  //

	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td colspan="4">');
	  	      $this->appendBufferLine($tableForm, '&nbsp;');
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');

	  	  //
	  	  //
	  	  //

	  	$this->appendBufferLine($tableForm, '</table>');
	  	
	  	return $tableForm;
	}
	
	/**
	 * inputBGColorFieldInfo.
	 *
	 * Format income information in a table for output.
	 * @param array $inputRequest is the inputRequest form fields.
	 * @param array $fieldList is the list of fields to process, in process order.
	 * @return string formatted result.
	 */
	public function inputBGColorFieldInfo(&$inputRequest, $fieldList=array())
	{
		$tableForm = '';
	    $this->appendBufferLine($tableForm, '<table width="100%" class="clsSubTable clsColorSchemeBGTable clsFontSchemeStd">');

	    foreach($fieldList as $fieldName)
	    {
	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataField clsFontSchemeStd">');
  	      	  $this->appendBufferLine($tableForm, sprintf('%s', $this->fieldTranslate[$fieldName]));
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataValue clsFontSchemeStd">');
	  	      $value = $inputRequest[$fieldName];
	  	      if ((! $value) ||
	  	          (! is_numeric($value)))
	  	      {
	  	      	  $value = 0;
	  	      }

  		  	    $this->appendBufferLine($tableForm, sprintf('<input class="clsInputData clsColorSchemeWtBlue clsFontSchemeStd" type="text" size="10" name="%s" value="%s"',
	  	    	                                            $fieldName,
	  	                                                    $value));
	  	      if ($fieldName == ' ')
	  	      {
  		  	    $this->appendBufferLine($tableForm, ' disabled');
	  	      }

  	      	  $this->appendBufferLine($tableForm, '><br>');

	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	    }

		$this->appendBufferLine($tableForm, '</table>');
		
		return $tableForm;
	}

	/**
	 * inputFieldInfo.
	 *
	 * Format dependent information in a table for output.
	 * @param array $inputRequest is the inputRequest form fields.
	 * @param array $fieldList is the list of fields to process, in process order.
	 * @return string formatted result.
	 */
	public function inputFieldInfo(&$inputRequest, $fieldList=array())
	{
		$tableForm = '';
	    $this->appendBufferLine($tableForm, '<table width="100%" style="border-style:none;" class="clsSubTable clsColorSchemeTable clsFontSchemeStd">');

	    foreach($fieldList as $fieldName)
	    {
	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataField clsFontSchemeStd">');

	  	      if ($fieldName == ' ')
	  	      {
	  	      	$this->appendBufferLine($tableForm, ' ');
	  	      }
	  	      else
	  	      {
	  	      	$this->appendBufferLine($tableForm, sprintf('%s', $this->fieldTranslate[$fieldName]));
	  	      }
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataValue clsFontSchemeStd">');

	  	      if ($fieldName == ' ')
	  	      {
	  		  	  $this->appendBuffer($tableForm, '&nbsp;');
	  	      }
	  	      else
	  	      {
	  	    	$value = $inputRequest[$fieldName];
	  	      	if ((! $value) ||
	  	            (! is_numeric($value)))
	  	      	{
	  	      	  $value = 0;
	  	        }

  		  	    $this->appendBufferLine($tableForm, sprintf('<input class="clsInputData clsColorSchemeWtBlue clsFontSchemeStd" type="text" size="10" name="%s" value="%s"><br>', 
	  	    	                                            $fieldName,
	  	        	                                        $value));
	  	      }

	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	    }

		$this->appendBufferLine($tableForm, '</table>');
		
		return $tableForm;
	}

	/**
	 * earningsInfo.
	 *
	 * Format earnings information in a table for output.
	 * @param array $inputRequest is the inputRequest form fields.
	 * @param array $fieldList is the list of fields to process, in process order.
	 * @return string formatted result.
	 */
	public function earningsInfo(&$inputRequest, $fieldList=array())
	{
		$tableForm = '';
	    $this->appendBufferLine($tableForm, '<table width="100%" class="clsSubTable clsColorSchemeBGTable clsFontSchemeStd">');

	    foreach($fieldList as $fieldName)
	    {
	  	  $this->appendBufferLine($tableForm, '<tr class="clsTR clsFontSchemeStd">');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataField clsFontSchemeStd">');

	  	      if ($fieldName == ' ')
	  	      {
	  	      	$this->appendBufferLine($tableForm, 'ITIN');
	  	      }
	  	      else
	  	      {
	  	      	$this->appendBufferLine($tableForm, sprintf('%s', $this->fieldTranslate[$fieldName]));
	  	      }
	  	    $this->appendBufferLine($tableForm, '</td>');

	  	    $this->appendBufferLine($tableForm, '<td class="clsTableDataValue clsFontSchemeStd">');

	  	      if ($fieldName == ' ')
	  	      {
	  		  	  $this->appendBuffer($tableForm, sprintf('<input class="clsInputData clsColorSchemeWtBlue clsFontSchemeStd" type="checkbox" name="itin_sp" value="%s" disabled>', 
	  	        	                                      $value));
	  	      }
	  	      else
	  	      {
	  	    	$value = $inputRequest[$fieldName];
	  	      	if ((! $value) ||
	  	            (! is_numeric($value)))
	  	      	{
	  	      	  $value = 0;
	  	        }

	  	        if ($fieldName == 'itin')
	  	        {
	  		  	  $this->appendBuffer($tableForm, sprintf('<input class="clsInputData clsColorSchemeWtBlue clsFontSchemeStd" type="checkbox" name="%s" value="%s"', 
	  	    	                                          $fieldName,
	  	        	                                      $value));
	  	          if ($inputRequest[$fieldName])
	  	          {
	  	        	$this->appendBuffer($tableForm, ' checked');
	  	          }

	  	          $this->appendBufferLine($tableForm, '>');
	  	        }
	  	        else
	  	        {
	  		  	  $this->appendBufferLine($tableForm, sprintf('<input class="clsInputData clsColorSchemeWtBlue clsFontSchemeStd" type="text" size="10" name="%s" value="%s"><br>', 
	  	    	                                              $fieldName,
	  	        	                                          $value));
	  	        }
	  	      }

	  	    $this->appendBufferLine($tableForm, '</td>');

	  	  $this->appendBufferLine($tableForm, '</tr>');
	    }

		$this->appendBufferLine($tableForm, '</table>');
		
		return $tableForm;
	}

	/**
     * appendBuffer.
     *
     * Append the input string to the supplied buffer.
     * @param string buffer buffer to append to (passed by reference)
     * @param string data string to append to the buffer
     * @return string result in buffer and returned as string
     */
	public function appendBuffer(&$buffer, $data)
	{
		$buffer .= $data;
	}

	/**
     * appendBufferLine.
     *
     * Append the input string to the supplied buffer followed by a newline sequence.
     * @param string buffer buffer to append to (passed by reference)
     * @param string data string to append to the buffer
     * @return string result in buffer and returned as string
     */
	public function appendBufferLine(&$buffer, $data='')
	{
		return $this->appendBuffer($buffer, $data . "\n");
	}

	/**
     * taxEngineFormBuffer.
     *
     * return a copy of the current taxEngineFormBuffer.
     * @return string current taxEngineFormBuffer contents
     */
	public function taxEngineFormBuffer()
	{
	  	return $this->taxEngineBuffer;
	}

	/**
     * inputFormBuffer.
     *
     * return a copy of the current inputFormBuffer.
     * @return string current inputFormBuffer contents
     */
	public function inputFormBuffer()
	{
	  	return $this->inputForm;
	}

	/**
     * getPageBuffer.
     *
     * Compose a page buffer from the inputFormBuffer and taxEngineResultBuffer.
     * @param boolean includeResult true = include the result buffer, else only the input buffer
     * @return string composed page output buffer contents
     */
	public function getPageBuffer($includeResult=true)
	{
		$columns = $includeResult ? 2 : 1;

		$buffer = '';
	  	$this->appendBufferLine($buffer, '<html><head><title>taxEngine Input Form</title>');
	  	$this->appendBufferLine($buffer, $this->cssSetup());
	  	$this->appendBufferLine($buffer, '</head>');
	  	$this->appendBufferLine($buffer, '<body class="clsBody clsColorSchemeWhiteBlue clsFontSchemeStd">');

	  	if ($columns == 1)
	  	{
	    	$this->appendBufferLine($buffer, $this->inputForm);
	    	$this->appendBufferLine($buffer, '</body></html>');
	    	return $buffer;
	  	}

	  	$this->appendBufferLine($buffer, '<table class="clsTable clsColorSchemeWhiteBlue clsFontSchemeStd" style="border-spacing:10px;">');

	  	$this->appendBufferLine($buffer, '<tr style="clsTR clsColorSchemeWhiteBlue clsFontSchemeStd" style="vertical-align:top;">');

	  	$this->appendBuffer($buffer, sprintf('<td class="clsTableDataField clsColorSchemeWhiteBlue clsFontSchemeStd" style="vertical-align:top;" colspan="%u"> ',
	  	                                     $columns));

	  	$this->appendBufferLine($buffer, '<p style="text-align:left;vertical-align:middle;">');
	  		$this->appendBufferLine($buffer, 
	  								'<img src="taxEngine256x256.png" 
               							  style="border:none;width:128px;height:128px;vertical-align:middle;text-align:center;" />');
	  		$this->appendBuffer($buffer,
	  							'&nbsp;<span class="taxengine">taxEngine</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="rushtaxFrom">by</span>&nbsp;&nbsp;');
	  		$this->appendBufferLine($buffer,
	  		                        '<span class="rush">rush</span><span class="tax">taxOS</span><br>');
	  	$this->appendBufferLine($buffer, '</p>');

	  	$this->appendBufferLine($buffer, '</td>');
	  	$this->appendBufferLine($buffer, '</tr>');

	  	$this->appendBufferLine($buffer, '<tr style="clsTR clsColorSchemeWhiteBlue clsFontSchemeStd" style="vertical-align:top;">');
	  	$this->appendBufferLine($buffer, '<td class="clsTableDataField clsColorSchemeWhiteBlue clsFontSchemeStd" style="vertical-align:top;">');
	    	$this->appendBufferLine($buffer, $this->inputForm);
	  	$this->appendBufferLine($buffer, '</td>');

	  	$this->appendBufferLine($buffer, '<td class="clsTableDataField clsColorSchemeWhiteBlue clsFontSchemeStd" style="vertical-align:top;">');
	    	$this->appendBufferLine($buffer, $this->taxEngineBuffer);
	  	$this->appendBufferLine($buffer, '</td>');

	  	$this->appendBufferLine($buffer, '</tr>');

	  	$this->appendBufferLine($buffer, '<tr >');
	  	$this->appendBufferLine($buffer, '<td colspan="2" class="copyright" style="vertical-align:top;">');
	    	$this->appendBufferLine($buffer, '<a href="http://www.rushtax.com/">Copyright &copy; 2009. RushTax, Inc.</a> By Jay Wheeler.');
	  	$this->appendBufferLine($buffer, '</td>');

	  	$this->appendBufferLine($buffer, '</tr></table>');
	  	$this->appendBufferLine($buffer, '</body></html>');
	  
	  	return $buffer;
	}

	/**
     * cssSetup.
     *
     * Create the style sheet for the application.
     * @return string style sheet data
     */
	private function cssSetup()
	{
		$css = '';
		$this->appendBufferLine($css, '<Style type="text/css">');

		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '.clsBody {text-align:left;vertical-align:top;} ');

		$this->appendBufferLine($css, '.clsTable {width:550px;text-align:center;vertical-align:top;border-style:solid;border-width:1px;} ');
		$this->appendBufferLine($css, '.clsTableResult {width:250px;text-align:center;vertical-align:top;border-style:solid;border-width:1px;} ');
		
		$this->appendBufferLine($css, '.clsTR {border-style:none;border-width:1px;} ');

		$this->appendBufferLine($css, '.clsSubTable {text-align:center;vertical-align:top;border-style:solid;border-width:1px;} ');
		$this->appendBufferLine($css, '.clsSubTR {border-style:none;border-width:1px;text-decoration:none;} ');

		$this->appendBufferLine($css, '.clsTableDataField {text-decoration:none;text-align:right;border-style:none;border-width:thin;} ');
		$this->appendBufferLine($css, '.clsTableDataValue {text-align:left;border-style:none;border-width:thin;} ');
		$this->appendBufferLine($css, '.clsTableBlockTitle {text-align:center;font-weight:bold;border-style:none;border-width:thin;text-decoration:underline;} ');

		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '.taxengine {font-size:32px;font-style:italic;font-weight:bold;color:#0168B5;text-align:center;margin-bottom:0in;} ');
		$this->appendBufferLine($css, '.rushtaxfrom {font-size:24px;font-weight:bold;color:#0168B5;text-align:center;margin-bottom:0in;} ');
		$this->appendBufferLine($css, '.rush {font-size:28px;font-style:italic;font-weight:bold;color:#0000FF;text-align:center;margin-bottom:0in;} ');
		$this->appendBufferLine($css, '.tax {font-size:32px;font-style:italic;font-weight:bold;color:#FF0000;text-align:center;margin-bottom:0in;} ');
		$this->appendBufferLine($css, '.copyright {font-family:arial,verdana;font-size:10px;font-weight:bold;color:#000062;text-align:center;margin-bottom:0in;} ');

		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '.clsRefundField {text-decoration:none;text-align:center;border-style:solid;border-width:thin;} ');

		$this->appendBufferLine($css, '.clsInputData {text-align:right;} ');
		$this->appendBufferLine($css, '.clsSelectData {text-align:left;} ');

		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '.clsColorSchemeTable     	{color:#000062;background-color:#FFFFFF;border-color:#000062;rev-color:#BFBFFF;rev-background:#000062;rev-border:#BFBFFF;} ');
		$this->appendBufferLine($css, '.clsColorSchemeBGTable   	{color:#000062;background-color:#FFFFCC;border-color:#000062;rev-color:#BFBFFF;rev-background:#000062;rev-border:#BFBFFF;} ');

		$this->appendBufferLine($css, '.clsColorSchemeResultRefund  {color:#000062;background-color:#F0FFFF;border-color:#000062;rev-color:#BFBFFF;rev-background:#000062;rev-border:#BFBFFF;} ');
		$this->appendBufferLine($css, '.clsColorSchemeResultOwe     {color:#000062;background-color:#FFF0FF;border-color:#000062;rev-color:#BFBFFF;rev-background:#000062;rev-border:#BFBFFF;} ');

		$this->appendBufferLine($css, '.clsColorSchemeWtBlue        {color:#000062;background-color:#FFFFFF;border-color:#000062;rev-color:#FFFFFF;rev-background:#000062;rev-border:#FFFFFF;} ');
		$this->appendBufferLine($css, '.clsColorSchemeWhiteBlue     {color:#000062;background-color:#FFFFFF;border-color:#000062;rev-color:#FFFFFF;rev-background:#000062;rev-border:#FFFFFF;} ');

		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '.clsFontSchemeStd    {font-family:arial,verdana;font-size:11px;font-weight:normal;}');
		$this->appendBufferLine($css, '.clsFontSchemeStdBld {font-family:arial,verdana;font-size:11px;font-weight:bold;}');
		$this->appendBufferLine($css, '.clsFontSchemeAlt    {font-family:verdana,arial;font-size:10px;font-weight:normal;}');
		$this->appendBufferLine($css, '.clsFontSchemeAltBld {font-family:verdana,arial;font-size:10px;font-weight:bold;}');

		$this->appendBufferLine($css, '.clsFontSchemeRefund {font-family:arial,verdana;font-size:11px;font-weight:bold;color:#006600;border-color:#006600;}');
		$this->appendBufferLine($css, '.clsFontSchemeOwe    {font-family:arial,verdana;font-size:11px;font-weight:bold;color:#FF0000;border-color:#FF0000;}');

		$this->appendBufferLine($css, '.clsRefundFontStdBld {font-family:arial,verdana;font-size:14px;font-weight:bold;}');
		//
		// ***************************************************************************************************************************************************************************************
		//

		$this->appendBufferLine($css, '</Style>');

		return $css;
	}

	/**
     * sendPageBuffer.
     *
     * Create and send the page buffer.
     * @return string current inputFormBuffer contents
     */
	public function sendPageBuffer()
	{
	  	print $this->getPageBuffer(true);
	}

}
