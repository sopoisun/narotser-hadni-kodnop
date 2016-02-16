<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class LabaRugi extends Fpdf{
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

    public function Header(){
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, $this->header, '', 1);
        $this->Ln(1);
    }

    public function WritePage()
    {
        $this->addPage();

        $tableGroup = collect($this->data)->groupBy('type');

        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(145, 8, "Keterangan", 1, 0);
        $this->Cell(35, 8, "Saldo", 1, 1);

        foreach($tableGroup as $key => $val)
        {
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(190, 8, strtoupper($key), 1, 1, '');
            $this->SetFont('Arial', '', 11);
            $no = 0;
            foreach($val as $v){
                $no++;
                $this->Cell(10, 8, $no, 1, 0, 'C');
                $this->Cell(145, 8, $v['keterangan'], 1, 0);
                $this->Cell(35, 8, number_format($v['nominal'], 0, ',', '.'), 1, 1, 'R');
            }
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(10, 8, "", 1, 0, 'C');
            $this->Cell(145, 8, "Total ".ucfirst($key), 1, 0);
            $this->Cell(35, 8, number_format(collect($val)->sum('nominal'), 0, ',', '.'), 1, 1, 'R');
        }

        $this->Cell(155, 8, "Laba/Rugi", 1, 0);
        $this->Cell(35, 8, number_format(collect($this->data)->sum('sum'), 0, ',', '.'), 1, 1, 'R');

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
