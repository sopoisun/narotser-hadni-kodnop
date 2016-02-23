<?php

namespace App\Libraries;

use App\Libraries\Fpdf\Fpdf;

class PenjualanGroup extends Fpdf{
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
    protected $dates = [];
    protected $first_column = 'Tanggal';
    protected $format_first_column = 'd M Y';
    protected $search_column = 'tanggal';
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
        $this->Cell(32, 8, $this->first_column, 1, 0);
        $this->Cell(30, 8, "Ttl Sale", 1, 0);
        $this->Cell(30, 8, "Ttl Rsv", 1, 0);
        $this->Cell(30, 8, "Ttl Srv", 1, 0);
        $this->Cell(30, 8, "Ttl Tax", 1, 0);
        $this->Cell(30, 8, "Ttl Tax Byr", 1, 0);
        //$this->Cell(30, 8, "Ttl", 1, 0);
        $this->Cell(25, 8, "Ttl Diskon", 1, 0);
        $this->Cell(30, 8, "Jml", 1, 0);
        $this->Cell(30, 8, "Ttl HPP", 1, 1);

        $no = 0;
        foreach($this->dates as $date)
        {
            $this->SetFont('Arial', '', 11);
            $no++;

            $this->Cell(10, 8, $no, 1, 0, 'C');
            $this->Cell(32, 8, $date->format($this->format_first_column), 1, 0);

            $idx = array_search($date->format($this->format_search), array_column($this->data, $this->search_column));

            // Data
            if(false !== $idx){
                $d = $this->data[$idx];

                $this->Cell(30, 8, number_format($d['total_penjualan'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['total_reservasi'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['total_service'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['pajak'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['pajak_pembayaran'], 0, ',', '.'), 1, 0, "R");
                //$this->Cell(30, 8, number_format($d['total_akhir'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(25, 8, number_format($d['diskon'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['jumlah'], 0, ',', '.'), 1, 0, "R");
                $this->Cell(30, 8, number_format($d['total_hpp'], 0, ',', '.'), 1, 1, "R");
            }else{
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(25, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 0, "R");
                $this->Cell(30, 8, "0", 1, 1, "R");
            }
        }

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 8, "", 1, 0, 'C');
        $this->Cell(32, 8, "Total", 1, 0);
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_penjualan'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_reservasi'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_service'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('pajak'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('pajak_pembayaran'), 0, ',', '.'), 1, 0, "R");
        //$this->Cell(30, 8, number_format(collect($this->data)->sum('total_akhir'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(25, 8, number_format(collect($this->data)->sum('diskon'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('jumlah'), 0, ',', '.'), 1, 0, "R");
        $this->Cell(30, 8, number_format(collect($this->data)->sum('total_hpp'), 0, ',', '.'), 1, 1, "R");

        $this->Output();
        exit();
    }

    public function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Application by ahmadrizalafani@gmail.com',0,1,'C');
    }
}
