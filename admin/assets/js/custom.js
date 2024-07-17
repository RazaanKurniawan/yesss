$(document).ready(function(){

    alertify.set('notifier','position', 'top-right');

    $(document).on('click', '.increment', function() {

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue)) {
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    $(document).on('click', '.decrement', function() {

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue) && currentValue > 1) {
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    function quantityIncDec(prodId, qty) {
    
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productIncDec' : true,
                'product_id' : prodId,
                'quantity' : qty
            },
            success: function(response) {
                var res = JSON.parse(response);
                console.log(res);
                
                if (res.status == 200) {
                    // window.location.reload();
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                } else {
                    $('#productArea').load(' #productContent');
                    alertify.error(res.message);
                }
            }
        });
    }

    // Tutor 15

    $(document).on('click', '.proceedToPlace', function(){
        var cphone = $('#cphone').val();
        var money = $('#money').val();
        var payment_mode = $('#payment_mode').val();
    
        if(payment_mode == ''){
            swal("Pilih Metode Pembayaran","Pilih Metode Pembayaran Kamu","warning");
            return false;
        }
    
        if(payment_mode == 'Uang Tunai' && money == ''){
            swal("Masukkan Nominal Yang Benar","Masukkan Nominal Yang Benar","warning");
            return false;
        }
    
        var data = {
            'proceedToPlaceBtn': true,
            'money': money,
            'cphone': cphone,
            'payment_mode': payment_mode,
        };
    
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function (response){
                var res = JSON.parse(response);
                if(res.status == 200){
                    if(payment_mode == 'Bayar Online'){
                        window.location.href = "orders-summary-onlinepay.php";
                    } else if(payment_mode == 'Uang Tunai'){
                        window.location.href = "orders-summary.php";
                    }
                }else if(res.status == 404){
                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: {
                                text: "Tambah Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"
                        }
                    })
                    .then((value) => {
                        switch(value){
                            case "catch":
                                $('#c_phone').val(cphone);
                                $('#addCustomerModal').modal('show');
                                break;
                            default: 
                        }
                    });
                }else{
                    swal(res.message, res.message, res.status_type)
                }
            }
        });
    });
    
    
    // Tutor 15

    // Tutor 16
    $(document).on('click', '.saveCustomer', function() {
        
        var c_name = $('#c_name').val();
        var c_class = $('#c_class').val();
        var c_phone = $('#c_phone').val();
        var c_email = $('#c_email').val();
    
        if(c_name !== '' && c_phone !== '') {
            if($.isNumeric(c_phone)) {
                var data = {
                    'saveCustomerBtn': true,
                    'name': c_name,
                    'class': c_class,
                    'phone': c_phone,
                    'email': c_email
                };
    
                $.ajax({
                    type: "POST",
                    url: "orders-code.php",
                    data: data,
                    success: function(response) {
                        var res = JSON.parse(response);
                        
                        if (res.status == 200) {
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModal').modal('hide');
                        } else if (res.status == 422) {
                            swal(res.message, res.message, res.status_type);
                        } else {
                            swal(res.message, res.message, res.status_type);
                        }
                    }
                });
                
            } else {
                swal("Enter Valid Phone Number", "", "warning");
            }
        } else {
            swal("All fields are required", "", "warning");
        }
    });
    

    // tutor 17
    $(document).on('click', '#saveOrder', function() {
        
        $.ajax({
            type: "POST",
            url: "orders-code.php", 
            data: {
                'saveOrder': true
            },
            success: function(response) {
                var res = JSON.parse(response);
                
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });

    });
    
   

    
});
function printMyBillingArea() {

    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>POS System in PHP</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print();  
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo){
    
    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html( elementHTML, {
        callback: function(){
            docPDF.save(invoiceNo+'.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });

}



