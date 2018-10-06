$(document).ready( function() {
    $('input').click(function(){
        $(this).select();
    });
    var now = new Date();

    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear() + "-" + (month) + "-" + (day);


    $('#table').DataTable();


    $('#datePicker').val(today);
    function myDelete(r) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("table").deleteRow(i);
    }
    bind();
});

function bind() {

    $(".qty").blur(update_price);
}

function roundNumber(number,decimals) {
    var newString;// The new rounded number
    decimals = Number(decimals);
    if (decimals < 1) {
        newString = (Math.round(number)).toString();
    } else {
        var numString = number.toString();
        if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
            numString += ".";// give it one at the end
        }
        var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
        var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
        var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
        if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
            if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
                while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                    if (d1 != ".") {
                        cutoff -= 1;
                        d1 = Number(numString.substring(cutoff,cutoff+1));
                    } else {
                        cutoff -= 1;
                    }
                }
            }
            d1 += 1;
        }
        if (d1 == 10) {
            numString = numString.substring(0, numString.lastIndexOf("."));
            var roundedNum = Number(numString) + 1;
            newString = roundedNum.toString() + '.';
        } else {
            newString = numString.substring(0,cutoff) + d1.toString();
        }
    }
    if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
        newString += ".";
    }
    var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
    for(var i=0;i<decimals-decs;i++) newString += "0";
    //var newNumber = Number(newString);// make it a number if you like
    return newString; // Output the result to the form field (change for your purposes)
}
function calculateTax(subtotal) {
    //var subTotal = document.orderform.subtotal.value; OR for dryer code:
    var stax = 0.13;
    tax = (subtotal * stax).toFixed(2);

    grandTotal(subtotal,tax);

    return $('#tax').html("$"+tax);

}

function grandTotal(subtotal,tax) {
    var grandTotal=0;
    grandTotal=(parseFloat(subtotal)+parseFloat(tax)).toFixed(2);
   return $('#total').html("$"+grandTotal);
}

function update_price() {

        var row = $(this).parents('.item-row');
        var price = row.find('.cost').val().replace("$","") * row.find('.qty').val();

        price = roundNumber(price,2);
        isNaN(price) ? row.find('.price').html("N/A") : row.find('.price').html("$"+price);
        update_total();
}
function update_total() {
    var total = 0;
    $('.price').each(function(i){

        price = $(this).html().replace("$","");
        console.log(price);
        if (!isNaN(price)) total += Number(price);
    });

    total = roundNumber(total,2);
    calculateTax(total)
    $('#subtotal').html("$"+total);

}


