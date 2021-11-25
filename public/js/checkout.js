$.ajaxSetup({
    headers: { 'X-TONGUE-SPICE-API-KEY': $('meta[name="app-data-id"]').attr('content') }
});
/**
 * CartDetails class inherits from CartData class
 *  depends on js/ShoppingCart.js
 */

class CartDetails extends CartData {
    /**
     * 
     * @param {string} cartDetailsSelector the jquery selector for the Cart Details Div
     */
    constructor(cartDetailsSelector, data) {
        super(data)
        this.cart = cartDetailsSelector
        this.renderCartItemCount()
        this.promoCode = data.promoCode ? data.promoCode.code : 'no promo code'
        this.promoCodeValue = data.promoCode? data.promoCode.value : 0
    }
    /**
     * @description displays the number of items in the cart on the right of the cart details title.
     */
    renderCartItemCount() {
        let count = this.getItemCount()
        $(this.cart).find('.itemCount').text(count)
    }
    /**
     * @description given an item data object, will generate the HTML for the Cart Details item
     * @param {Object} item 
     * @returns {string} a Jquery Object referencing a Cart Detail DOM item
     */
    createDetailItem(item) {
        return $(`
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">${item.title}</h6>
                    <small class="text-muted">${item.qty} x ${item.price}</small>
                </div>
                <span class="text-muted">$${(item.qty * item.price).toFixed(2)}</span>
            </li>
        `)
    }
    /**
     * @description Will render the Shopping Cart Items to the Cart Detials section of the document.
     */
    render() {
        if(this.items.length == 0){
            $('.billing_content-hidable').hide()
            $('.no-items-message').show()
            return
        }

        for (let item of this.items) {
            let $template = this.createDetailItem(item)
            $(this.cart + ' #cart-item-list').prepend($template)
        }
        this.renderPromoCode()
        this.renderTotal()

        this.initEventListenders()
    }
    /**
     * @description will add the promo code to the Cart Details section on the document and update the price
     */
    renderPromoCode() {
        let $this = $(this.cart + ' #cart-item-list li.promoCode')
        $this.find('.code').text(this.promoCode)
        $this.find('.code-value .value').text(this.promoCodeValue)
    }
    /**
     * Initializes event handlers for events used in the cart details section of the document.
     */
    initEventListenders(){
        // when promo code is submitted, update the price on the DOM
        $('#promoCodeRedeem').submit(function(e){
            e.preventDefault()
            if (!e.currentTarget.checkValidity()) {
                e.stopPropagation()
            }
            else{
                var $btn = $(e.currentTarget).find('button[type="submit"]')
                $btn.attr("disabled",'disabled').find('.redeem-text').hide()
                $btn.find('.fa').show()
                let promoCode = $('#promoCodeToRedeem').val()
                var _this = this;
                $.post(BASE_PATH+'/api/promo.php', 
                    {code:promoCode, cart_id: this.data.id},
                    function(response){
                        
                        if(response.status){

                            _this.promoCode = response.promoCode.code_str
                            _this.promoCodeValue += parseFloat(response.promoCode.value)
                            _this.renderPromoCode()
                            _this.renderTotal()
                            
                            $(e.currentTarget).fadeOut(function(){
                                $(e.currentTarget).remove()
                            })                            
                        }
                        else{
                            $btn.removeAttr('disabled').find('.redeem-text').show()
                            $btn.find('.fa').hide()
                            $('#promoCodeToRedeem').addClass('is_invalid').removeClass('is_valid').val('').focus()
                        }
                        Alert(response)
                })

            }
            e.currentTarget.classList.add('was-validated')
        }.bind(this))
    }
    /**
     * @description Will display the total on the Cart Details section of the document
     */
    renderTotal(){
        let grossTotal = this.items.length? this.items
            .map(item=>item.price * item.qty)
            .reduce((a,b) => a + b) : 0.00, // if no items then set total to 0
            tax = grossTotal * 0.05
        let total = (grossTotal + tax) - this.promoCodeValue
        $(this.cart).find('.netTotal .tax .value').text(tax.toFixed(2))
        $(this.cart).find('.netTotal .netValue .value').text(total.toFixed(2))
    }
}

// create a new CartDetails instance
let cart = new CartDetails('#cartDetails', cartData)
// render the Cart items to the Cart Detials section
cart.render()


/**
 * @description will hide the form and the cart details and show a success message after the form is submitted
 */
function postPaymentProcessed(){
    var email = $('[name="email"]').val()
    $('#billingForm').toggleClass('was-validated')[0].reset()
    $('.email_sent').text(email)
    cart.clearAll()
    $('.billing_content-hidable').fadeOut(()=> $('#form-submit-success').show())
}
// handles the submission of the form
$('#billingForm').submit(function(e){
    
    
    if (!e.currentTarget.checkValidity()) {
        e.preventDefault()
        Alert({status:false, message:'Please fill out the form.'})
        $('#billingForm input:invalid')[0].focus()
    }
    else $(this).find('button[type="submit"]').attr('disabled','disabled')
    e.currentTarget.classList.add('was-validated')
})
// if the is same address check box is clicked then we check if it was checked ( hide the shipping address section) or unchecked (show the shipping address section)
$('input[name="isSameAddress"]').change(function(e){
    if($(e.currentTarget).is(':checked')){
        $('div.shippingAddressSection').fadeOut().find(':required').removeAttr('required')
    }else{
        $('div.shippingAddressSection').fadeIn().find('input,select').attr('required','')
    }
})
// handles changes to the payment method radio field group, if paypal is selcected then we hide the credit card information section
$('input[name="paymentMethod"]').change(function(e){
    if($(this).val() == "pp"){
        $('#payPalPayment').fadeIn()
        $('.cardPayment').fadeOut().find('input:required').removeAttr('required')
    }
    else{
        $('#payPalPayment').fadeOut()
        $('.cardPayment').fadeIn()
            .find('input').attr('required','')
    }
})