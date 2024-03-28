<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel - Paystack Integration</title>
</head>
<body>
    <h2>Product: Laptop</h2>
    <h3>Price: 10 NGN</h3>

    <form id="paymentForm">
        <div class="form-submit">
          <button type="submit" onclick="payWithPaystack()"> Pay Now</button>
        </div>
      </form>
      
      <script src="https://js.paystack.co/v1/inline.js"></script>

      <script>
            const paymentForm = document.getElementById('paymentForm');
            paymentForm.addEventListener("submit", payWithPaystack, false);

            function payWithPaystack(e) {
            e.preventDefault();

            let handler = PaystackPop.setup({
                key: "{{ env('PAYSTACK_PUBLIC_KEY') }}", // Replace with your public key
                email: "diroyiv464@otemdi.com",
                amount: 20 * 100,
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Laptop",
                            variable_name: "laptop",
                            value: "laptop"
                        },
                        {
                            display_name: "Quantity",
                            variable_name: "quantity",
                            value: "1"
                        }
                    ]
                },
                onClose: function(){
                alert('Window closed.');
                },
                callback: function(response){
                // let message = 'Payment complete! Reference: ' + response.reference;
                // alert(message);
                // alert(JSON.stringify(response));
                window.location.href = "{{ route('callback') }}" + response.redirecturl;
                }
            });

            handler.openIframe();
            }
      </script>
</body>
</html>