<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 3/19/2018
 * Time: 9:37 AM
 */

namespace App\Functions;


class BinarySearch
{
	function binarysearch($arraycheckno,$findcheckno)
	{
		$start = 0;
		$end   = Count($arraycheckno)-1;
		
		$start = 0;
		$end = count($arraycheckno)-1;
		
		while ($start <= $end)
		{
			
			$mid = (int) floor(($start + $end)/2);
			
			if ( $arraycheckno[$mid] ==  $findcheckno)
			{
				return $mid;
			}
			elseif ( $findcheckno < $arraycheckno[$mid] )
			{
				$end = $mid-1;
			}
			else
			{
				$start = $mid+1;
			}
			
		}
		return -1;
	}
	
	function  realindex($arraycheckno,$findcheckno,$index1)
	{
		if($index1!=0)
		{
			for($x1=$index1;$x1>=0;$x1--)
			{
				if($findcheckno != $arraycheckno[$x1])
				{
					return $x1 + 1;
				}
			}
		}
		else
		{
			return 0;
		}
	}
	
	function matchingdis($arraycheckno,$findcheckno,$arraybook,$arraybank)
	{
		$x = Array();
		$count = count($arraycheckno);
		$index12 = $this->binarysearch($arraycheckno,$findcheckno);
		$realindex = $this->realindex($arraycheckno,$findcheckno,$index12);
		
		if($index12 !=-1)
		{
			if($realindex == null)
			{
				$realindex = 0;
			}
			else
			{
				$realindex = $realindex;
			}
			
			for($i1 = $realindex; $i1<$count;$i1++)
			{
				$checkme = $arraycheckno[$i1];
				if($checkme!=null or $checkme!=0)
				{
					if($findcheckno ==$checkme )
					{
						$expl        = explode("|",$arraybook[$i1]);
						
						$cv_no       = $expl[0];
						$checkno     = $expl[1];
						$cv_date     = $expl[2];
						$check_date  = $expl[3];
						$checkamount = $expl[4];
						
						$expl2       = explode("|",$arraybank);
						$bankdate    = $expl2[0];
						$bankcheckno = $expl2[1];
						$bankdes     = $expl2[2];
						$bankamount  = $expl2[3];
						$bankTotal   = $expl2[4];
						
						$tagamt      = $bankamount;
						$tagdate     = $bankdate;
						$tagdes      = $bankdes;
						$tagcheck    = $bankcheckno;
						$tagtotal    = $bankTotal;

						if(trim($checkno) == trim($bankcheckno))
						{
							$occurences = array_count_values($x);
							if(in_array($checkno, $x))
							{
								$var = $occurences[$checkno];
							}
							else
							{
								$var = 0;
							}
							
							if($var<=0)
							{
								$x[] = $checkno;
								
							}
							else
							{
								$tagamt    = "";
								$tagdate   = "";
								$tagdes    = "";
								$tagcheck  = "";
							}
						}
					}
				}
			}
		}
	}
}