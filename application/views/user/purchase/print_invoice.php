<!DOCTYPE html>
<html lang="en">
<head>
  <title>Purchase Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="d-flex">
  <div class="w-50">
    <span class="mb-2"><b>Purchase From</b></span><br><br>
    <span>Retailer Name - </span><?php echo $retailer->name;?><br>
    <span>Mobile - </span><?php echo $retailer->contact_no;?><br>
  </div>
  <div class="w-50 text-right">
    <h2>INVOICE</h2>
    <b>Date - </b><?php echo $invoice->product_purchase_date;?>
    <br><b>Invoice No - <?php echo $invoice->invoice_id;?></b>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Product Code / Name</th>
          <th>Quantity</th>
          <th>Unit of Measurement</th>
          <th>Expiry Date</th>
          <th>Cost Price <br>(Per piece)</th>
          <th>Selling Price <br>(Per piece)</th>
        </tr>
      </thead>
      <tbody class="tbody">
      <?php if($products)
      { foreach ($products as $pro) { ?>
        <tr>
          <td><?php echo $pro->product_id.''.$pro->product_name;?></td>
          <td><?php echo $pro->quantity;?></td>
          <td>Unit</td>
          <td><?php echo $pro->expiry_date;?></td>
          <td><?php echo $pro->cost_price;?></td>
          <td><?php echo $pro->sale_price;?></td>
        </tr>
      <?php }  } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="5" style="text-align:right" >Total:</th>
        <th id="total"><?php echo $invoice->amt_paid;?></th>
      </tr>
    </tfoot>
  </table>
  <div class="row">
    <div class="col-md-12 text-center">
      <button type="button" class="btn btn-warning" id="print">Print Invoice</button>
    </div>
  </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $('#print').click(function(){
    window.print();
    return false;
  });
</script>
</body>
</html>
