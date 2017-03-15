<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class SalePlan extends Fpdf{
    public function __construct($data=[])
    {
        parent::__construct('L', 'mm', 'A4');
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
        $this->Cell(70, 8, "Nama", 1, 0);
        $this->Cell(25, 8, "Type", 1, 0);
        $this->Cell(35, 8, "Diperlukan", 1, 0);
        $this->Cell(35, 8, "Stok", 1, 0);
        $this->Cell(35, 8, "Dibeli", 1, 0);
        $this->Cell(30, 8, "Harga", 1, 0);
        $this->Cell(30, 8, "Subtotal", 1, 1);

        $this->setFillColor(255, 99, 99);

        $no = 0;
        $total = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;
            $total += $v['harga']*$v['stok_yg_dibeli'];

            $enableColor = 0;
            if( $v['stok_yg_dibeli'] <= 0 ){
                $enableColor = 1;
            }

            $this->Cell(10, 8, $no, 1, 0, 'C', $enableColor);
            $this->Cell(70, 8, $v['nama'], 1, 0, 'L', $enableColor);
            $this->Cell(25, 8, $v['type'], 1, 0, 'L', $enableColor);
            $this->Cell(35, 8, $v['qty_diperlukan'].' '.$v['satuan_pakai'], 1, 0, 'L', $enableColor);
            $this->Cell(35, 8, $v['stok'].' '.$v['satuan_pakai'], 1, 0, 'L', $enableColor);
            $this->Cell(35, 8, $v['stok_yg_dibeli'].' '.$v['satuan_pakai'], 1, 0, 'L', $enableColor);
            $this->Cell(30, 8, number_format($v['harga'], 0, ',', '.'), 1, 0, 'R', $enableColor);
            $this->Cell(30, 8, number_format($v['harga']*$v['stok_yg_dibeli'], 0, ',', '.'), 1, 1, 'R', $enableColor);
        }

        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(230, 8, "Total", 1, 0);
        $this->Cell(30, 8, number_format($total, 0, ',', '.'), 1, 1, 'R');

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
