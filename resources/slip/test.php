<?php
require './fpdf.php';
$con = mysqli_connect('localhost','root','','faker');

class PDF extends FPDF
{
    public function header()
    {
        // $this->Image('inec.png', 0, 2, 50);
        // $this->Image('ng.jpg', 178, 6, 28);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 200, 0);
        $this->Cell(0, 5, 'ALISHA DESIGN AND TEXTTILE', 0, 20, 'C');
        $this->Cell(139, 5, '', 0, 1);
        $this->SetTextColor(0, 0, 150);
        $this->Ln(20);

        #999999
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    } //footer

} //
$query = ("SELECT * FROM faker LIMIT 10");
$sql = mysqli_query($con,$query);
$files = mysqli_fetch_all($sql, MYSQLI_ASSOC);

foreach ($files as $file) :
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell(0, 6, 'Orders', 0, 1);
    $pdf->ln(1);
    $id = 'malah';
    
    $pdf->SetFillColor(0, 0, 0);
    $pdf->Cell(18, 6, 'OrderId', 1);
    $pdf->Cell(70, 6, 'Items', 1,1);
   
    
    $pdf->SetFont('Times', '', 13);
    $pdf->Cell(18, 6, $file['id'], 1,);
    $pdf->Cell(70, 6, $file['email'], 1);

    
    
    $pdf->Output($id, 'I');  $id = 'malah';
endforeach;
?>