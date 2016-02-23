<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class Penjualan extends Fpdf{
  	protected $data      = [];
    protected $header    = '';

  	function __construct($data = []) {
    	parent::__construct('L', 'mm', 'A4');
        foreach($data as $key => $val){
            if( isset($this->$key) ){
                $this->$key = $val;
            }
        }
	}

	public function rptDetailData () {
		//
		$border = 0;
		$this->AddPage();
		$this->SetAutoPageBreak(true,60);
		$this->AliasNbPages();
		$left = 10;

		$this->SetFont("", "B", 11);
		$this->SetX($left);
        $this->Cell(0, 10, $this->header, 0, 1,'L');

		$h = 8;
		$left = 40;
		$top = 80;
		#tableheader
		$this->SetFillColor(200,200,200);
		$left = $this->GetX();
		$this->Cell(10, $h, 'No', 1, 0, 'C',true);
		$this->SetX($left += 10); $this->Cell(30, $h, 'No. Nota', 1, 0, 'L',true);
		$this->SetX($left += 30); $this->Cell(15, $h, 'Status', 1, 0, 'L',true);
		$this->SetX($left += 15); $this->Cell(20, $h, 'Type Tax', 1, 0, 'L',true);
		$this->SetX($left += 20); $this->Cell(25, $h, 'Type Bayar', 1, 0, 'L',true);
        $this->SetX($left += 25); $this->Cell(23, $h, 'Ttl Sale', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(23, $h, 'Ttl Rsv', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(23, $h, 'Srv Cost', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(23, $h, 'Pajak', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(23, $h, 'Pjk Byr', 1, 0, 'L',true);
        //$this->SetX($left += 23); $this->Cell(23, $h, 'Total', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(15, $h, 'Diskon', 1, 0, 'L',true);
        $this->SetX($left += 15); $this->Cell(23, $h, 'Jumlah', 1, 0, 'L',true);
        $this->SetX($left += 23); $this->Cell(23, $h, 'Ttl HPP', 1, 1, 'L',true);
		//$this->Ln(20);

		$this->SetFont('Arial', '', 10);
		$this->SetWidths(array(10, 30, 15, 20, 25, 23, 23, 23, 23, 23, 15, 23, 23));
		$this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
		$no = 1; $this->SetFillColor(255);
		foreach ($this->data as $baris) {
			$this->Row(
				array($no++,
				$baris['nota'],
				$baris['state'],
				$baris['type_tax'],
				ucwords($baris['type_bayar']),
				number_format($baris['total_penjualan'], 0, ',', '.'),
                number_format($baris['total_reservasi'], 0, ',', '.'),
                number_format($baris['total_service'], 0, ',', '.'),
                number_format($baris['pajak'], 0, ',', '.'),
                number_format($baris['pajak_pembayaran'], 0, ',', '.'),
                //number_format($baris['total_akhir'], 0, ',', '.'),
                number_format($baris['diskon'], 0, ',', '.'),
                number_format($baris['jumlah'], 0, ',', '.'),
                number_format($baris['total_hpp'], 0, ',', '.'),
			));
		}

        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths(array(10, 90, 23, 23, 23, 23, 23, 15, 23, 23));
		$this->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $this->Row(
            array("", "Total",
            number_format(collect($this->data)->sum('total_penjualan'), 0, ',', '.'),
            number_format(collect($this->data)->sum('total_reservasi'), 0, ',', '.'),
            number_format(collect($this->data)->sum('total_service'), 0, ',', '.'),
            number_format(collect($this->data)->sum('pajak'), 0, ',', '.'),
            number_format(collect($this->data)->sum('pajak_pembayaran'), 0, ',', '.'),
            //number_format(collect($this->data)->sum('total_akhir'), 0, ',', '.'),
            number_format(collect($this->data)->sum('diskon'), 0, ',', '.'),
            number_format(collect($this->data)->sum('jumlah'), 0, ',', '.'),
            number_format(collect($this->data)->sum('total_hpp'), 0, ',', '.'),
        ));

	}

    public function WritePage()
    {
        $this->SetAutoPageBreak(false);
	    $this->AliasNbPages();
	    $this->SetFont("helvetica", "B", 10);
	    //$this->AddPage();

	    $this->rptDetailData();

	    $this->Output();
        exit(0);
    }

  	private $widths;
	private $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=8*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,8,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
} //end of class
