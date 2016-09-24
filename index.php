<?php 
    require_once('config.php');
    require_once('utils.php');
?>
<html>
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <?php 
        $params = array(
            'email' => $PAGSEGURO_EMAIL,
            'token' => $PAGSEGURO_TOKEN
        );
        $header = array();

        $response = curlExec($PAGSEGURO_API_URL."/sessions", $params, $header);
        $json = json_decode(json_encode(simplexml_load_string($response)));
        $sessionCode = $json->id;
    ?>
</head>
<body>

<div class="container">
    <div class="row">
        
        <div class="col-md-4">
        
            <div class="panel panel-default">
                <div class="panel-heading" >
                    <div class="row" >
                        <h3 class="panel-title" >Pague com PagSeguro</h3>                            
                    </div>                    
                </div>

                <div class="panel-body">
                    <form role="form" action="./pay.php" method="POST">

                        <input type="hidden" name="brand">
                        <input type="hidden" name="token">
                        <input type="hidden" name="senderHash">


                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">Nº Cartão</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" name="cardNumber" placeholder="Valid Card Number" autocomplete="cc-number" required autofocus value="4111 1111 1111 1111"/>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-7 col-md-7">
                                <div class="form-group">
                                    <label for="cardExpiry">Validade</label>
                                    <input type="tel" class="form-control" name="cardExpiry" placeholder="MM / YY" autocomplete="cc-exp" required value="12/2030"/>
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-5 pull-right">
                                <div class="form-group">
                                    <label for="cardCVC">CVV</label>
                                    <input type="tel" class="form-control" name="cardCVC" placeholder="CVV" autocomplete="cc-csc" required value="123"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="subscribe btn btn-success btn-lg btn-block" type="submit">Pagar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
            
        </div>            
        
    </div>
</div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
  <script>

        $("button:submit").click(function(){
            var senderHash = PagSeguroDirectPayment.getSenderHash();
            $("input[name='senderHash']").val(senderHash);
        });

        jQuery(function($) {
          PagSeguroDirectPayment.setSessionId('<?php echo $sessionCode;?>');

          PagSeguroDirectPayment.getPaymentMethods({
            success: function(json){
                console.log(json);
            },
            error: function(json){
                console.log(json);
              var erro = "";
              for(i in json.errors){
                erro = erro + json.errors[i];
              }
              alert(erro);
            },
            complete: function(json){
            }
          });

          PagSeguroDirectPayment.getBrand({
            cardBin: $("input[name='cardNumber']").val().replace(/ /g,''),
            success: function(json){
              var brand = json.brand.name;

              $("input[name='brand']").val(brand);

              console.log(brand);
              
              var param = {
                cardNumber: $("input[name='cardNumber']").val().replace(/ /g,''),
                brand: brand,
                cvv: $("input[name='cardCVC']").val(),
                expirationMonth: $("input[name='cardExpiry']").val().split('/')[0],
                expirationYear: $("input[name='cardExpiry']").val().split('/')[1],
                success: function(json){
                  var token = json.card.token;
                  $("input[name='token']").val(token);
                  console.log("Token: " + token);
                },
                error: function(json){
                    console.log(json);
                },
                complete:function(json){
                }
              }

              PagSeguroDirectPayment.createCardToken(param);
            },
            error: function(json){
              console.log(json);
            },
            complete: function(json){
            }
          });

        });

    </script>
</body>
</html>