<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class MutasiStok extends Fpdf{
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
    protected $type = 'Produk';

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
        $this->Cell(60, 8, "Nama ".$this->type, 1, 0);
        $this->Cell(20, 8, "Before", 1, 0);
        $this->Cell(20, 8, "Buy", 1, 0);
        $this->Cell(20, 8, "Sale", 1, 0);
        $this->Cell(20, 8, "Adj (+)", 1, 0);
        $this->Cell(20, 8, "Adj (-)", 1, 0);
        $this->Cell(20, 8, "Sisa Stok", 1, 1);

        $no = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(60, 8, $v['nama'].' ('.$v['satuan'].')', 1, 0);
            $this->Cell(20, 8, round($v['before'], 2), 1, 0, 'C');
            $this->Cell(20, 8, round($v['pembelian'], 2), 1, 0, 'C');
            $this->Cell(20, 8, round($v['penjualan'], 2), 1, 0, 'C');
            $this->Cell(20, 8, round($v['adjustment_increase'], 2), 1, 0, 'C');
            $this->Cell(20, 8, round($v['adjustment_reduction'], 2), 1, 0, 'C');
            $this->Cell(20, 8, round($v['sisa'], 2), 1, 1, 'C');
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
