<link rel="stylesheet" type="text/css" href="../../resources/css/signup.css" />

<?php

$form = $this->beginWidget('CActiveForm', array(
    'id'                   => 'contactform',
    'htmlOptions'          => array('class' => 'rounded'),
    'enableAjaxValidation' => false,
    'clientOptions'        => array('validateOnSubmit' => true),
    'errorMessageCssClass' => 'error',
));
?>

<br/>
<?php

$buttonName = 'Next';

$fieldOption = array('class' => 'input');

?>

<div class="field">
        <?php echo $form->labelEx($modelTransferToMobile, Yii::t('zii', 'Method')) ?>
        <?php echo $form->textField($modelTransferToMobile, 'method', array('class' => 'input', 'readonly' => true)) ?>
        <?php echo $form->error($modelTransferToMobile, 'method') ?>
</div>


<div class="field">
        <?php echo $form->labelEx($modelTransferToMobile, Yii::t('zii', 'Country')) ?>
        <?php echo $form->textField($modelTransferToMobile, 'country', array('class' => 'input', 'readonly' => true)) ?>
        <?php echo $form->error($modelTransferToMobile, 'country') ?>
</div>

<div class="field">
        <?php echo $form->labelEx($modelTransferToMobile, Yii::t('zii', 'Type')) ?>
        <?php echo $form->textField($modelTransferToMobile, 'type', array('class' => 'input', 'readonly' => true)) ?>
        <?php echo $form->error($modelTransferToMobile, 'type') ?>
</div>



<?php if (Yii::app()->session['is_interval'] == true): ?>
    <div id='is_interval'>
        <br>

        <div class="field">
            <?php echo $form->labelEx($modelTransferToMobile, 'amountValuesEUR', array('label' => 'Paid Amount (' . Yii::app()->session['currency_orig'] . ')')); ?>
            <?php echo $form->textField($modelTransferToMobile, 'amountValuesEUR',
    array(
        'class'   => 'input',
        'id'      => 'amountfielEUR',
        'onkeyup' => 'showPriceEUR()',
        'style'   => 'color:blue; font-size:20',
    )) ?>
            <?php echo $form->error($modelTransferToMobile, 'amountValuesEUR') ?>

        </div>

        <div class="field">
            <?php echo $form->labelEx($modelTransferToMobile, 'amountValuesBDT', array('label' => 'Receive Amount (' . Yii::app()->session['currency_dest'] . ')')); ?>
            <?php echo $form->textField($modelTransferToMobile, 'amountValuesBDT',
    array(
        'class'   => 'input',
        'id'      => 'amountfielBDT',
        'onkeyup' => 'showPriceBDT()',
        'style'   => 'color:blue; font-size:20',
    )) ?>
            <?php echo $form->error($modelTransferToMobile, 'amountValuesBDT') ?>
             <p class="hint"><?php echo $amountDetails ?></p>

        </div>

     </div>
<br>
<br>
<?php endif?>

<div class="sp-page companies__content" >
      <div class="company__list" id='productList'>
        <?php $id = 0;?>
        <?php foreach (Yii::app()->session['amounts'] as $key => $value): ?>
            <label for="2" class="company__row" id="productLabel<?php echo $id ?>">
                    <input type="radio"  id="productinput<?php echo $id ?>" name="amountValues" value="<?php echo $key ?>">
                    <div  class="company__logo-container" onclick="handleChange1(<?php echo $id ?>,<?php echo $id + 1 ?>);" id='product<?php echo $id ?>' ><?php echo $value ?></div>
                </label>
                <?php $id++;?>
        <?php endforeach;?>

      </div>
</div>


<div class='field' id="aditionalInfo" style="display:none; border:0">
    <label>Additional Info</label>
    <div id="aditionalInfoText" class="input" style="border:0; width:650px" ></div>
</div>



<div class='field' id="divsellingPrice" style="display:none; border:0">
    <label>Selling Price</label>
    <div id="sellingPrice" class="input" style="border:0; width:650px" ></div>
</div>

<div class="controls" id="sendButton">
<?php echo CHtml::submitButton(Yii::t('zii', 'Next'), array(
    'class'   => 'button',
    'onclick' => 'button2(event)',
    'id'      => 'secondButton'));
?>
<input class="button" style="width: 80px;" onclick="window.location='../../index.php/transferToMobile/read';" value="Cancel">
<input id ='buying_price'  class="button" style="display:none; width: 100px;" onclick="getBuyingPrice()" value="R" readonly>

</div>
<div class="controls" id="buttondivWait"></div>

<?php $this->endWidget();?>


<script type="text/javascript">

    function getBuyingPrice(argument) {


        if (document.getElementById('amountfielBDT') && document.getElementById('amountfielBDT').value > 0) {
             valueAmoutBDT = document.getElementById('amountfielBDT').value;
            var id = 0;
        }else{
            var id =  document.getElementById('productinput'+window.productInputSelected).value;
            valueAmoutBDT = 0;
        }


        amountValuesEUR = document.getElementById('amountfielEUR').value;

        if (document.getElementById('buying_price').value != 'R') {
            document.getElementById('buying_price').value = 'R';
        }else{


                var http = new XMLHttpRequest()

                http.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('buying_price').value = this.responseText;
                    }
                };

                http.open("GET", "../../index.php/transferPayment/getBuyingPrice?id="+id+"&valueAmoutBDT="+valueAmoutBDT+"&valueAmoutEUR="+amountValuesEUR+"&country=<?php echo isset($_POST['TransferToMobile']['country']) ? $_POST['TransferToMobile']['method'] : 0 ?>",true)
                http.send(null);

        }


    }

    function button2(e) {

        if (!document.getElementById('amountfielEUR')) {
            document.getElementById("sendButton").style.display = 'none';
            document.getElementById("buttondivWait").innerHTML = "<font color = green>Wait! </font>";
            return;
        }

        valueAmoutEUR = document.getElementById('amountfielEUR').value;

        if (valueAmoutEUR <0) {

            document.getElementById("sendButton").style.display = 'none';
            document.getElementById("buttondivWait").innerHTML = "<font color = green>Wait! </font>";
        }

    }
    function showPriceEUR() {


        for (var i = 0; i < 50 ; i++) {
            if ( document.getElementById('productLabel'+i)) {
                document.getElementById('productLabel'+i).style.backgroundColor = '#fff';
            }else{
                break;
            }
        }


        valueAmoutEUR = document.getElementById('amountfielEUR').value;

        if (valueAmoutEUR > 0) {
            var http = new XMLHttpRequest()

            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    if (this.responseText =='invalid') {
                        document.getElementById('secondButton').style.display = 'none';
                    }else{
                        document.getElementById('secondButton').style.display = 'inline';
                    }

                    document.getElementById('buying_price').style.display = 'inline';
                    document.getElementById('buying_price').value = 'R';
                    document.getElementById('amountfielBDT').value = this.responseText;
                    }
                };

            http.open("GET", "../../index.php/transferPayment/convertCurrency?currency=EUR&amount="+valueAmoutEUR+"&country=<?php echo $_POST['TransferToMobile']['country']; ?>",true)
            http.send(null);
        }else{
            document.getElementById('amountfielEUR').value = 'Invalid';
            document.getElementById('secondButton').style.display = 'none';
        }


    }

    function showPriceBDT() {


        for (var i = 0; i < 50 ; i++) {
            if ( document.getElementById('productLabel'+i)) {
                document.getElementById('productLabel'+i).style.backgroundColor = '#fff';
            }else{
                break;
            }
        }

        valueAmoutBDT = document.getElementById('amountfielBDT').value;
         //verify if inserted amount is allowed
        min = '<?php echo Yii::app()->session['allowedAmountmin']; ?>';
        max = '<?php echo Yii::app()->session['allowedAmountmax']; ?>';


        if (parseInt(valueAmoutBDT) < parseInt(min)) {
           document.getElementById('amountfielEUR').value = 'Invalid Min';
           document.getElementById('secondButton').style.display = 'none';
        }
        else if (parseInt(valueAmoutBDT) > parseInt(max)) {

            console.log(parseInt(valueAmoutBDT) + ' > '+parseInt(max));
           document.getElementById('amountfielEUR').value = 'Invalid Max';
           document.getElementById('secondButton').style.display = 'none';
        }
        else if (valueAmoutBDT > 0) {
            var http = new XMLHttpRequest()

            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.getElementById('amountfielEUR').value = this.responseText;
                    document.getElementById('buying_price').style.display = 'inline';
                    document.getElementById('buying_price').value = 'R';
                    document.getElementById('secondButton').style.display = 'inline';
                    }
                };

            http.open("GET", "../../index.php/transferPayment/convertCurrency?currency=BDT&amount="+valueAmoutBDT+"&country=<?php echo $_POST['TransferToMobile']['country']; ?>",true)
            http.send(null);
        }else{
            document.getElementById('amountfielEUR').value = 'Invalid';
            document.getElementById('secondButton').style.display = 'none';
        }


    }


    function handleChange1(argument,total) {

        document.getElementById('secondButton').style.display = 'inline';

        if (document.getElementById('amountfielEUR') ) {
            document.getElementById('amountfielEUR').value = '';
        }
        if (document.getElementById('amountfielBDT')) {
            document.getElementById('amountfielBDT').value = '';
        }


        for (var i = 0; i < 30 ; i++) {
            if ( !document.getElementById('productLabel'+i)) {
                break;
            }
            document.getElementById('productLabel'+i).style.backgroundColor = '#fff';
        }
         document.getElementById('productLabel'+argument).style.backgroundColor = '#dd8980';

        document.getElementById('productinput'+argument).checked = true;
        window.productInputSelected = argument

        document.getElementById('buying_price').style.display = 'inline';
        document.getElementById('buying_price').value = 'R';

        idProduct = document.getElementById('productinput'+argument).value;
        var http = new XMLHttpRequest()
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('aditionalInfo').style.display = 'inline';
                document.getElementById('aditionalInfoText').innerHTML = this.responseText;


            }
        }
        http.open("GET", "../../index.php/TransferPayment/getProductTax?id="+idProduct);
        http.send(null);
    }

</script>


<style type="text/css">
    #contactform {
        margin: 0 auto;
        width: 720px;
        padding: 5px;
        background: #f0f0f0;
        overflow: auto;
        /* Border style */
        border: 1px solid #cccccc;
        -moz-border-radius: 7px;
        -webkit-border-radius: 7px;
        border-radius: 7px;
        /* Border Shadow */
        -moz-box-shadow: 2px 2px 2px #cccccc;
        -webkit-box-shadow: 2px 2px 2px #cccccc;
        box-shadow: 2px 2px 2px #cccccc;
    }
    .company__row {
        display: inline-block;
        -webkit-box-shadow: 0 0 5px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, .1);
        background-color: #fff;
        position: relative;
        vertical-align: top
    }

    .company__row:nth-child(odd) {
        margin-left: 0
    }

    .company__row:hover {
        background: #f7f7f7
    }

    .company__row input[type=radio] {
        display: none
    }


    .company__logo-container {
        text-align: center
    }

    .company__row--disabled .company__logo {
        filter: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale");
        filter: #999;
        -webkit-filter: grayscale(100%);
        -webkit-transition: all .6s ease;
        transition: all .6s ease;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        opacity: .41
    }

    .company__row {
        width: 23%;
        -webkit-box-shadow: none;
        box-shadow: none;
        padding: 20px 30px;
        cursor: pointer
    }

    .company__row:first-child,
    .company__row:nth-child(2) {
        border-top: 0!important
    }

    .company__row:nth-child(odd) {
        border-right: 1px solid hsla(0, 0%, 80%, .25)
    }

    .company__row:nth-child(2n),
    .company__row:nth-child(odd) {
        border-top: 1px solid hsla(0, 0%, 80%, .25)
    }


    .company__list {
        -webkit-box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .1);
        -webkit-border-radius: 4px;
        border-radius: 4px;
        overflow: hidden
    }

    .company__logo {
        vertical-align: middle
    }

    .company__logo-container {
        height: 58px;
        line-height: 58px
    }

    .company__row--blank {
        height: 108px
    }
</style>