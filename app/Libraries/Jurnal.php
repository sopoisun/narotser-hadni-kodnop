<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class Jurnal extends Fpdf{
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

        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(75, 8, "Keterangan", 1, 0);
        $this->Cell(35, 8, "Debet", 1, 0);
        $this->Cell(35, 8, "Kredit", 1, 0);
        $this->Cell(35, 8, "Saldo", 1, 1);

        foreach($this->data as $key => $val)
        {
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(190, 8, date('d M Y', strtotime($key)), 1, 1, '');
            $this->SetFont('Arial', '', 11);
            $no = 0;
            foreach($val as $v){
                $no++;
                $this->Cell(10, 8, $no, 1, 0, 'C');
                $this->Cell(75, 8, $v['keterangan'], 1, 0);
                $this->Cell(35, 8, is_numeric($v['debet']) ? number_format($v['debet'], 0, ',', '.') : $v['debet'], 1, 0, 'R');
                $this->Cell(35, 8, is_numeric($v['kredit']) ? number_format($v['kredit'], 0, ',', '.') : $v['kredit'], 1, 0, 'R');
                $this->Cell(35, 8, is_numeric($v['saldo']) ? number_format($v['saldo'], 0, ',', '.') : $v['saldo'], 1, 1, 'R');
            }
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
