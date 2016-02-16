<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class ReportKaryawan extends Fpdf{
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
    protected $dates = [];
    protected $first_column = 'Tanggal';
    protected $format_first_column = 'd M Y';
    protected $search_column = '_date';
    protected $format_search = 'Y-m-d';

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
        $this->Cell(100, 8, $this->first_column, 1, 0);
        $this->Cell(30, 8, "Total Penjualan", 1, 1);

        $no = 0;
        foreach($this->dates as $date)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(100, 8, $date->format($this->format_first_column), 1, 0);

            $idx = array_search($date->format($this->format_search), array_column($this->data->toArray(), $this->search_column));

            // Data
            if(false !== $idx){
                $d = $this->data[$idx];
                $this->Cell(30, 8, number_format($d['total_penjualan'], 0, ',', '.'), 1, 1, "R");
            }else{
                $this->Cell(30, 8, "0", 1, 1, "R");
            }
        }

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(100, 8, "Total", 1, 0);
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_penjualan'), 0, ',', '.'), 1, 1, "R");

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
