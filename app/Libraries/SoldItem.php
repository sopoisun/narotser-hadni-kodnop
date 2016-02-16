<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class SoldItem extends Fpdf{
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
        $this->Cell(90, 8, "Nama Produk", 1, 0);
        $this->Cell(30, 8, "HPP (Avg)", 1, 0);
        $this->Cell(30, 8, "Harga (Avg)", 1, 0);
        $this->Cell(15, 8, "Terjual", 1, 0);
        $this->Cell(30, 8, "Ttl HPP", 1, 0);
        $this->Cell(30, 8, "Subtotal", 1, 0);
        $this->Cell(30, 8, "Laba", 1, 0);
        $this->Cell(15, 8, "%", 1, 1);

        $no = 0;
        foreach($this->data as $v)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(90, 8, $v['nama'], 1, 0);
            $this->Cell(30, 8, number_format($v['hpp'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(30, 8, number_format($v['harga_jual'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(15, 8, $v['terjual'], 1, 0, 'C');
            $this->Cell(30, 8, number_format($v['total_hpp'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(30, 8, number_format($v['subtotal'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(30, 8, number_format($v['laba'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell(15, 8, $v['laba_procentage'].' %', 1, 1);
        }

        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(165, 8, "Total", 1, 0);
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_hpp'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(30, 8, number_format(collect($this->data)->sum('subtotal'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(30, 8, number_format(collect($this->data)->sum('laba'), 0, ',', '.'), 1, 0, 'R');
        $this->Cell(15, 8, "", 1, 0);

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
