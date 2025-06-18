<?php
  $is_comma_price = az_get_config('is_comma_price', 'config_app');
  if (strlen(strpos(app_url(), 'giovarta') ) > 0 ) {
    $is_comma_price = 1;
  }

  $is_comma = 'az_thousand_separator';
  if ($is_comma_price) {
     $is_comma = 'az_thousand_separator_decimal';
  } 
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cetak Laporan Neraca</title>
	<style type="text/css">
		html, body {
			width: 21cm;
			font-family: 'Helvetica';
			text-align: center;
			font-size: 12px;
		}

		.td{
			width: 55px;
		}

		.td2{
			width: 120px;
		}

		.valign{
			vertical-align: top;
		}

		.stripe {
		  border-collapse: collapse;
		  width: 100%;
		}

		.stripe td, .stripe th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}

		.stripe th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  background-color: #144e7c;
		  color: white;
		}
	</style>
</head>
<body onload="window.print();">
	<div class="margin">
		<hr>
			<h1><?php echo az_get_config('app_name', 'config') ?></h1>
			<h2>Laporan Neraca</h2>
			<h4>Per Tanggal <?php echo $date2;?></h4>
		<hr>

		<table class="stripe table table-hover table-bordered table-sm table-condensed" style="border-collapse:collapse; margin-top:20px; font-size:12px;" width="100%" cellpadding="3">
			<thead>
        <tr>
          <th colspan="4" style="text-align: center; color: #ffffff;">Akun</th>
        </tr>
	    </thead>
			<tbody>
        <?php
          $the_grand_total = 0;
          $the_grand_total_modal = 0;
          
          foreach ((array)$arr_data as $data_key => $data_value) {
        ?>
            <tr style="font-size:14px;">
              <td align="left" colspan="4" style="background-color: #a5d7ff;"><b><?php echo $data_value['account_name'];?></b></td>
            </tr>

            <?php
              foreach ((array)$data_value['arr_detail'] as $detail_key => $detail_value) {
            ?>
                <tr>
                  <td align="left" colspan="4" style="padding-left: 40px;"><b><?php echo $detail_value['account_name_sub'];?></b></td>
                </tr>

                <?php
                  $the_sub_total = 0;
                  $the_sub_total_modal = 0;

                  foreach ((array)$detail_value['arr_detail_sub'] as $sub_key => $sub_value) {
                    
                    if ($sub_value['saldo_aset'] != 0 || $sub_value['saldo_modal'] != 0 || $sub_value['account_name'] == "Pendapatan Periode Ini") {

                      $saldo_aset = az_crud_number($sub_value['saldo_aset']);
                      $saldo_modal = az_crud_number($sub_value['saldo_modal']);

                      $the_sub_total += (double) $saldo_aset;
                      $the_sub_total_modal += (double) $saldo_modal;
                      $the_grand_total += (double) $saldo_aset;
                      $the_grand_total_modal += (double) $saldo_modal;
                ?>
                      <tr>
                        <td align="left" style="width: 25%; padding-left: 60px;"><?php echo $sub_value['account_code'];?></td>
                        <td align="left" style="width: 45%;"><?php echo $sub_value['account_name'];?></td>
                        <td align="right" style="width: 15%;">
                          <?php 
                            if ($saldo_aset != '' && $saldo_aset > 0) {
                              echo $is_comma($saldo_aset);
                            } 
                            else if ($saldo_aset < 0) {
                              echo '('.$is_comma(abs((double) $saldo_aset)).')';
                            } 
                          ?>
                        </td>
                        <td align="right" style="width: 15%;">
                          <?php 
                            if ( ($saldo_modal != '' && $saldo_modal > 0) || ($sub_value['account_name'] == "Pendapatan Periode Ini" && $saldo_modal == 0)  ) {
                              echo $is_comma($saldo_modal);
                            }
                            else if ($saldo_modal < 0) {
                              echo '('.$is_comma(abs((double) $saldo_modal)).')';
                            }
                          ?>
                        </td>
                      </tr>
                  
                  <?php 
                    }
                  } 
                ?>

                <tr style="font-weight:bold;">
                  <td colspan="2" style="padding-left: 40px;">Total <?php echo $detail_value['account_name_sub'];?></td>
                  <td align="right">
                    <?php 
                      if ($data_key == 0) {
                        if ($the_sub_total >= 0) {
                          echo $is_comma($the_sub_total);
                        } 
                        else if ($the_sub_total < 0) {
                          echo '('.$is_comma(abs($the_sub_total)).')';
                        }
                      } 
                    ?>
                  </td>
                  <td align="right">
                    <?php 
                      if ($data_key == 1) {
                        if ($the_sub_total_modal >= 0) {
                          echo $is_comma($the_sub_total_modal);
                        }
                        else if ($the_sub_total_modal < 0) {
                          echo '('.$is_comma(abs($the_sub_total_modal)).')';
                        }
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="4" style="height: 10px;"></td>
                </tr>

            <?php 
              }
          } 
        ?>

        <tr style="font-weight:bold; font-size:14px;">
          <td colspan="2">Total</td>
          <td align="right">
            <?php 
              if ($the_grand_total >= 0) {
                echo $is_comma($the_grand_total);
              } 
              else if ($the_grand_total < 0) {
                echo '('.$is_comma(abs($the_grand_total)).')';
              }
            ?>
          </td>
          <td align="right">
            <?php 
              if ($the_grand_total_modal >= 0) {
                echo $is_comma($the_grand_total_modal);
              } 
              else if ($the_grand_total_modal < 0) {
                echo '('.$is_comma(abs($the_grand_total_modal)).')';
              }
            ?>
          </td>
        </tr>
      </tbody>
    </table>
	</div>
</body>
</html>