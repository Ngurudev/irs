<?php
include '../config.php';

/*call the FPDF library*/
require 'watermark.php';

$a = '&#8358;';


class PDF extends PDF_Rotate
{
    public function header()
    {
        // $this->Image('inec.png', 0, 2, 50);
        // $this->Image('ng.jpg', 178, 6, 28);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(0, 200, 0);
        $this->Cell(0, 5, 'ALISHA DESIGN AND TEXTTILE', 10, 20, 'C');
        $this->Cell(139, 5, '', 0, 1);
        $this->SetTextColor(0, 0, 150);
        //Put the watermark
        $this->SetFont('Arial','B',30);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(50,160,'A L I S H A DESIGN AND TEXTILES',35,20,'C');
        $this->Ln(20);
    
        #999999
    }
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    } //footer

} 

$sn = 1;
$query = query("SELECT * FROM orders as a, customers_users as b ");
confirm($query);
$files = mysqli_fetch_all($query, MYSQLI_ASSOC);

$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',15);
$pdf->Cell(71 ,5,'Ship To',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'Details',0,1);

$pdf->SetFont('Arial','',10);
foreach ($files as $file) {   
    $out = $file['fullname'].' Reciept @Alisha.pdf';
    $address = $file['address'];
    $name = $file['fullname'];
    $lga = $file['lga'];
    $orign = $file['nationality'].', ' .$file['state'].', '.$lga;
}
$pdf->Cell(130 ,5,$orign,0,0);
$pdf->Cell(28 ,5,'Customer Name:',0,0);
$pdf->Cell(34 ,5,$name,0,1);

$pdf->Cell(130 ,5,$address.','.$lga,0,0);
$pdf->Cell(28 ,5,'Invoice Date:',0,0);
$pdf->Cell(34 ,5,Date('d / M / Y'),0,1);

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(28 ,5,'Invoice No:',0,0);
$pdf->Cell(34 ,5,'ORD001',0,1);






$pdf->Cell(50 ,10,'',0,1);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,6,'S/N',1,0,'C');
$pdf->Cell(80 ,6,'Description',1,0,'C');
$pdf->Cell(23 ,6,'Qty',1,0,'C');
$pdf->Cell(30 ,6,'Unit Price',1,0,'C');
$pdf->Cell(20 ,6,'Subtotal',1,0,'C');
$pdf->Cell(25 ,6,'TransactionID',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$i= 1;
$sum = 0;
$pdf->SetFont('Arial','',10);
foreach ($files as $file) :
   
    $json_orders = json_decode($file["ordersItems"]);
    $name = $file['fullname'];
    $total = $file['totalPrice'];
    $sum +=$json_orders->sub;
    $pdf->Cell(10 ,6,$i++,1,0);
    $pdf->Cell(80 ,6,$json_orders->product_title,1,0);
    $pdf->Cell(23 ,6,$json_orders->items_quantity,1,0,'R');
    $pdf->Cell(30 ,6,'N '.number_format($json_orders->product_price),1,0,'R');
    $pdf->Cell(20 ,6,'N '.number_format($json_orders->sub),1,0,'R');
    $pdf->Cell(25 ,6,$file['transaction_id'],1,1,'R');
    
endforeach;
    

    $pdf->Cell(118 ,6,'',0,0);
    $pdf->Cell(25 ,6,'Subtotal',0,0);
    $pdf->Cell(45 ,6,'N '.number_format($sum),1,1,'R');

    $pdf->Output($out, 'I');

?>