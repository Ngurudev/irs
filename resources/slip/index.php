<?php
// ini_set('memory_limit', -1);

 include '../config.php';
// $email = $_SESSION['email'];
// if (!isset($email)) {
//     redirect('../../login.php');
// }
require 'watermark.php';
// if (isset($_GET['print'])) {

class PDF extends PDF_Rotate
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
        //Put the watermark
        $this->SetFont('Arial','B',20);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(35,100,'A L I S H A DESIGN AND TEXTILES',35);
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
        // $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    } //footer

} //end of class
$sn = 1;
$query = query("SELECT * FROM orders as a, customers_users as b");
confirm($query);
$files = mysqli_fetch_all($query, MYSQLI_ASSOC);

    $pdf = new PDF();
    $id = 'malah';

$pdf->AddPage();
$pdf->SetFont('Times', 'B', 13);
$pdf->Cell(0, 6, 'Orders', 0, 1);
$pdf->ln(1);

$pdf->SetFillColor(0, 0, 0);
$pdf->Cell(18, 6, 'S/N', 1);
$pdf->Cell(18, 6, 'OrderId', 1);
$pdf->Cell(40, 6, 'Items', 1);
$pdf->Cell(25, 6, 'Price', 1);
$pdf->Cell(25, 6, 'Quantity', 1);
$pdf->Cell(25, 6, 'Subtotal', 1);
$pdf->Cell(40, 6, 'TransactionID', 1,1);
foreach ($files as $file) :
   
        $json_orders = json_decode($file["ordersItems"]);
        $name = $file['fullname']; 
$pdf->SetFont('Times', '', 13);
$pdf->Cell(18, 6, $sn++, 1);
$pdf->Cell(18, 6, $file['orderId'], 1);
$pdf->Cell(40, 6, $json_orders->product_title, 1);
$pdf->Cell(25, 6, number_format($json_orders->product_price), 1);
$pdf->Cell(25, 6, number_format($json_orders->items_quantity), 1);
$pdf->Cell(25, 6, number_format($json_orders->sub), 1);
$pdf->Cell(40, 6, $file['transaction_id'], 1,1);


endforeach;
$pdf->Ln(5);
$pdf->Cell(0, 5, 'ALISHA DESIGN AND TEXTTILE', 0, 1, 'C');
$pdf->Cell(0, 6, $name, 0,1, 'C');
$pdf->Output($id, 'F');