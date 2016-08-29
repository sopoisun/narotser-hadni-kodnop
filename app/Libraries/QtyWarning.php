<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class QtyWarning extends Fpdf{
    public function __construct($data=[])
    {
        parent::__construct('P', 'mm', 'A4');
		$this->SetMargins(10, 10);

        foreach($data as $key => $val){
            if( isset($this->$key) ){
                $this->$key = $val;
            }
        }
    }

    protected $data = [];
    protected $header = '';
    protected $text = '';

    public function Header(){
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, $this->header, '', 1);
        $this->Ln(1);
    }

    public function WritePage()
    {
        $this->addPage();

        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(130, 8, "Nama ".$this->text, 1, 0);
        $this->Cell(50, 8, "Stok", 1, 1, 'C');

        $no = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(130, 8, $v['nama'], 1, 0);
            $this->Cell(50, 8, round($v['stok'], 2), 1, 1, 'C');
        }

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
