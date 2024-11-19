<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_status'])) {
  header('Location: index');
  exit();
} else {

  require_once '../core/autoload.php';
  require_once '../core/Database.php';
  require_once '../common/CRUD.php';
  require_once '../phpqrcode/qrlib.php';
  include '../fpdf/fpdf.php';
  require_once 'includes/students_data.php';

  $ref    =   trim($_POST['ref']);

  $HistoriesQuery   = $db->prepare("
                                    SELECT *,
                                    student_fees.id as feeId,
                                    student_fees.payment_type as paymentDesc
                                    FROM payments_history
                                    INNER JOIN student_fees ON student_fees.id = payments_history.paymentId
                                    WHERE payments_history.transaction_id = ?");
  $HistoriesQuery->execute([$ref]);
  $PRecp  =   $HistoriesQuery->fetchObject();
  $amt    =   number_format($PRecp->amount_paid, 2);
  $desc   =   $PRecp->payment_type;
  $xpId   =   $PRecp->reference;
  $pgr    =   $program;
  $appID  =   strtoupper($uid);
  $fname  =   $User->last_name . ' ' . $User->first_name . ' ' . $User->middle_name;
  $ss     =   $PRecp->payment_session;
  $sc     =   $course;
  $date   =   date('F g, Y', strtotime($PRecp->created_at));
  // $qrdata =   $appID . $User->last_name . '_' . $desc . '_' . $amt;
  $qrdata = "Student Payment Details: $appID - $fname\nDescription: $desc\nAmount Paid: $amt\nDate: $date";
  $qrImagePath = '../qrimages/qrcode_' . $ref . '.png';
  QRcode::png($qrdata, $qrImagePath, QR_ECLEVEL_L, 4);
  $qrCodeUrl = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($qrdata);


  // Download the QR code image from the URL and save it to the local file path
  // file_put_contents($qrImagePath, file_get_contents($qrCodeUrl));

  // Create a PDF object
  $pdf = new FPDF();
  $pdf->AddPage();

  // Adding background Images
  $pdf->Image('../assets/images/logo/fuo_bg.png', 0, 0, 210, 297);

  $pdf->AddFont('Times-Roman', '', 'times.php');
  $pdf->AddFont('Times-Roman', 'B', 'times.php');
  $pdf->AddFont('Times-Roman', 'I', 'times.php');
  // Set font
  $pdf->SetFont('Arial', 'B', 16);

  // Adding heading logo and school name
  $pdf->Image('../assets/images/logo/fuo_logo.jpg', 10, 7, 20, 27);

  $pdf->SetXY(30, 10);
  $pdf->Cell(0, 10, 'FOUNTAIN UNIVERSITY OSOGBO, OSUN STATE.', 0, 1, 'L');

  $pdf->SetFont('Arial', '', 14);
  $pdf->SetXY(30, 17);
  $pdf->Cell(0, 10, 'P.M.B 4491, Oke-Osun, Osogbo.', 0, 1, 'L');

  $pdf->SetXY(30, 24);
  $pdf->Cell(0, 10, 'Motto: Faith, Knowledge and Service', 0, 1, 'L');

  $pdf->SetFont('Arial', 'B', 14);
  // Add content to the PDF
  $pdf->SetXY(0, 45);
  $pdf->Cell(0, 20, 'PAYMENT RECEIPT', 0, 1, 'C'); // Title  

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Transaction ID:', 0);
  $pdf->Cell(60, 10, $ref, 0, 1);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Payment Reference:', 0);
  $pdf->Cell(60, 10, $xpId, 0, 1);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Student Name:', 0);
  $pdf->Cell(60, 10, $fname, 0, 1);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Matric Number:', 0);
  $pdf->Cell(60, 10, $appID, 0, 1);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Programme :', 0);
  $pdf->Cell(60, 10, $pgr, 0, 1);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(40, 10, 'Course :', 0);
  $pdf->Cell(60, 10, $sc, 0, 1);

  $pdf->Cell(40, 10, 'Payment Date:', 0);
  $pdf->Cell(60, 10, $date, 0, 1);

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(0, 10, 'Payment Description', 0, 1, 'L');

  $pdf->Line($pdf->GetX(), $pdf->GetY(20), $pdf->GetX() + 180, $pdf->GetY()); // Adjust the length as needed
  $pdf->Ln(4);

  $pdf->Cell(40, 10, 'Session:', 0);
  $pdf->Cell(50, 10, $ss, 0, 1);

  $pdf->Cell(50, 10, 'Amount Paid (Naira) :', 0);
  $pdf->Cell(50, 10, $amt, 0, 1);

  $pdf->Cell(50, 10, 'Purpose', 0);
  $pdf->Cell(50, 10, $desc, 0, 1);

  $pdf->Line($pdf->GetX(), $pdf->GetY(20), $pdf->GetX() + 180, $pdf->GetY());
  $pdf->Ln(5);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(0, 10, 'This receipt has been verified at bursary. Payment value written and stamped', 0);

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(0, 10, '', 0, 1, 'L');

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(0, 15, '', 0, 1, 'L');

  // Draw a straight line under the text
  $pdf->Line($pdf->GetX(50), $pdf->GetY(100), $pdf->GetX() + 60, $pdf->GetY()); // Adjust the length as needed
  $pdf->Ln();

  $pdf->SetFont('Times-Roman', '', 12);
  $pdf->Cell(0, -20, 'Bursary Signature and Stamp', 0, 1, 'L');

  // Embed the locally saved QR code image
  $pdf->Image($qrImagePath, 10, 230, 40, 40);

  ob_end_clean();
  $pdf->Output("$User->first_name-$desc-receipt.pdf", 'I');

  // Clean up: You can optionally delete the locally downloaded image if no longer needed
  unlink($qrImagePath);
}
