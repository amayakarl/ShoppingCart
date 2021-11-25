<?php
use Mpdf\Mpdf;
$file = '12000000'.$reciept["id"];

function getItemsHtml($rpt){
    $cnt = '';
    foreach($rpt["cart"]["items"] as $item){
        $cnt .= '
            <tr>
                <td>
                    '.$item["title"].'
                </td>
                <td>
                    '.$item["qty"].'
                </td>
                <td>
                    '.$item["price"].'
                </td>
                <td>
                    '.$item["item_total"].'
                </td></tr>';
    }

    return $cnt;
}

$pdf = new MPdf();

$content = '<div>
    <h1>Tongue Spice Reciept</h1>
    <table style="width:100%;">
        <tbody>
            <tr>
                <td style="width:50%;">
                    <h5>Billing</h5>
                    
                        <p>
                            '.$reciept["bill_address"]["firstname"].' '.$reciept["bill_address"]["lastname"].'
                        </p>
                        <p>
                            '.$reciept["bill_address"]["email"].'
                        </p>
                        <p>
                            '.$reciept["bill_address"]["address_line"].'
                        </p>
                        <p>
                            '.$reciept["bill_address"]["address_line2"].'
                        </p>
                        <p>
                            '.$reciept["bill_address"]["district"].'
                        </p>
                    
                </td>
                <td style="width:50%;">
                    <h5>Tongue Spice</h5>                    
                        <p>Reciept #'.$file.'</p>
                        <p>'.$reciept["bill_address"]["created_ts"].'</p>
                        <p>tonguespice.com</p>
                        <p>support@ts.com</p>                    
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Shipping</h5>
                    
                        <p>
                            '.$reciept["ship_address"]["firstname"].' '.$reciept["ship_address"]["lastname"].'
                        </p>
                        <p>
                            '.$reciept["ship_address"]["email"].'
                        </p>
                        <p>
                            '.$reciept["ship_address"]["address_line"].'
                        </p>
                        <p>
                            '.$reciept["ship_address"]["address_line2"].'
                        </p>
                        <p>
                            '.$reciept["ship_address"]["district"].'
                        </p>
                    
                </td>
            </tr>
        </tbody>
    </table>
    <h5>Your items:</h5>
    <table style="width:100%;">
        
        <tbody>
        <tr>
            <td style="width:40%; font-weight:bold;">Title</td>
            <td style="width:20%; font-weight:bold;">Qty</td>
            <td style="width:20%; font-weight:bold;">Price</td>
            <td style="width:20%; font-weight:bold;">Charge</td>
        </tr>
            '.getItemsHtml($reciept).'
        </tbody>
    </table>
    <h5>Your totals:</h5>
    <table>
        <tbody>
            <tr>
                <td>Sub total: $</td>
                <td>'.$reciept["subtotal"].'</td>
            </tr>
            <tr>
                <td>Tax (5%): $</td>
                <td>'.$reciept["tax_amount"].'</td>
                
            </tr>
            <tr>
                <td>Promo Code:</td>
                <td>(-$'.$reciept["promo_code_value"].')</td>
            </tr>
            <tr>
                <td>Total: $</td>
                <td>'.number_format(floatval($reciept["total"]) - floatval($reciept["promo_code_value"]), 2).'</td>
            </tr>
        </tbody>
    </table>

    <h5>Payment</h5>
    <ul style="list-style: none; padding-left:0px; margin-left:0px;">
        <li>Method: '.$reciept["payment"]["payment_type"].'</li>
        <li style="text-decoration:underline;">Payment Amount: $'.$reciept["payment"]["amount_paid"].'</li>
    </ul>
    <p>Thank you for your patronage!</p>    
</div>';
$pdf->writeHTML($content);
ob_clean();
$pdf->Output();