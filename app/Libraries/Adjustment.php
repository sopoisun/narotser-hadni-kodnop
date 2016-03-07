<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class Adjustment extends Fpdf{
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

        # Adjustment Incread
        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 8, "Daftar Adjustment (+) ", 1, 1);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(65, 8, "Nama Barang", 1, 0);
        $this->Cell(20, 8, "Type", 1, 0);
        $this->Cell(25, 8, "Harga (Avg)", 1, 0);
        $this->Cell(35, 8, "Stok", 1, 0);
        $this->Cell(35, 8, "Total ", 1, 1);

        if( count($this->data['increase']) ){
            $no = 0;
            foreach($this->data['increase'] as $v)
            {
                $this->SetFont('Arial', '', 11);
                $no++;

                $this->Cell(10, 8, $no, 1, 0, 'C');
                $this->Cell(65, 8, $v['nama'], 1, 0);
                $this->Cell(20, 8, ucfirst($v['type']), 1, 0);
                $this->Cell(25, 8, number_format($v['harga_avg'], 0, ',', '.'), 1, 0, 'R');
                $this->Cell(35, 8, $v['qty_stok'].' '.$v['satuan'], 1, 0, 'C');
                $this->Cell(35, 8, number_format($v['subtotal'], 0, ',', '.'), 1, 1, 'R');
            }

            $this->Cell(10, 8, "", 1, 0, 'C');
            $this->Cell(145, 8, "Total", 1, 0);
            $this->Cell(35, 8, number_format(collect($this->data['increase'])->sum('subtotal'), 0, ',', '.'), 1, 1, 'R');
        }else{
            $this->SetFont('Arial', '', 11);
            $this->Cell(190, 8, "Tidak ada data untuk ditampilkan", 1, 1, 'C');
        }

        $this->ln(10);

        # Adjustment Reduction
        // table header
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 8, "Daftar Adjustment (-) ", 1, 1);
        $this->Cell(10, 8, "#", 1, 0, 'C');
        $this->Cell(65, 8, "Nama Barang", 1, 0);
        $this->Cell(20, 8, "Type", 1, 0);
        $this->Cell(25, 8, "Harga (Avg)", 1, 0);
        $this->Cell(35, 8, "Stok", 1, 0);
        $this->Cell(35, 8, "Total ", 1, 1);

        if( count($this->data['reduction']) ){
            $no = 0;
            foreach($this->data['reduction'] as $v)
            {
                $this->SetFont('Arial', '', 11);
                $no++;

                $this->Cell(10, 8, $no, 1, 0, 'C');
                $this->Cell(65, 8, $v['nama'], 1, 0);
                $this->Cell(20, 8, ucfirst($v['type']), 1, 0);
                $this->Cell(25, 8, number_format($v['harga_avg'], 0, ',', '.'), 1, 0, 'R');
                $this->Cell(35, 8, $v['qty_stok'].' '.$v['satuan'], 1, 0, 'C');
                $this->Cell(35, 8, number_format($v['subtotal'], 0, ',', '.'), 1, 1, 'R');
            }

            $this->Cell(10, 8, "", 1, 0, 'C');
            $this->Cell(145, 8, "Total", 1, 0);
            $this->Cell(35, 8, number_format(collect($this->data['reduction'])->sum('subtotal'), 0, ',', '.'), 1, 1, 'R');
        }else{
            $this->SetFont('Arial', '', 11);
            $this->Cell(190, 8, "Tidak ada data untuk ditampilkan", 1, 1, 'C');
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
