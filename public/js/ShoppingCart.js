$.fn.setRemoveAnimation = function(state){
    
    if(state){
        this.find('.fa-trash').hide()
        this.find('.fa-spinner').show()
        this.attr('disabled','disabled')
    }
    else{
        this.removeAttr('disabled')

        this.find('.fa-trash').show()
        this.find('.fa-spinner').hide()
    }
}
$.fn.setSaveAnimation = function(state){
    if(state){
        this.find('.fa-save').hide()
        this.find('.fa-spinner').show()
        this.attr('disabled','disabled')
    }
    else{
        this.removeAttr('disabled')
        this.find('.fa-save').show()
        this.find('.fa-spinner').hide()
    }
}

/**
 * Base Cart Data class, for doing base operations on the cart data
 */
class CartData{
    constructor(cartData){    
        
            this.data = cartData; 
            this.items = cartData.items; 
    }
    /**
     * @description gets the number of items in the cart
     * @returns {number} number of items in the cart
     */
    getItemCount(){
        return this.items.length
    }
    /**
     * @description returns a sauce item given its id
     * @param {number|string} id the Sauce Item Id 
     * @returns {SauceItem}
     */
    getItemById(id){
        return this.items.filter(item=>item.id == id)[0] ?? null
    }
    /**
     * 
     * @param {number} id the sauce item ID
     * @returns the index of the sauce item in the cart array
     */
    getItemIndexById(id){
        for(let i = 0; i < this.items.length; i++){
            if(id == this.items[i].id) 
                return i
        }
        return false
    }
    /**
     * @description clears all items from the the shopping cart and resets the local storage
     * @returns {ShoppingCart}
     */
    clearAll(){
        console.log('clear called')
        this.items = []
        return this
    }
}
/**
 * Inherits from CartData class
 * Responsible for the shopping cart feature
 */
class ShoppingCart extends CartData{
    /**
     * @param {string} showSelector the show cart toggle button jquery selector
     * @param {string} cartSelector the Shopping Cart jquery selector
     */
    constructor(cartData, cartSelector){
        super(cartData)
        this.cart = cartSelector
        this.initEventHandlers()        
    }
    /**
     * @description removes an item from the cart list of items
     * @param {number|string} itemId the id of the item to remove from the cart
     * @returns {ShoppingCart} 
     */
    removeItemFromCart(itemId, $removeEl){
        var _this = this;
        $removeEl.setRemoveAnimation(true)
        $.ajax({
            url: BASE_PATH+`/api/cart.php?cart_id=${_this.data.id}&item_id=${itemId}`,
            type: 'DELETE',
            contentType: 'application/json',
            success: function(response) {
                if(response.status){

                    // Do something with the result
                    _this.items = _this.items.filter(item => item.id !== itemId)
                    
                    
                    $('.cart-list-item[data-id="'+itemId+'"]').fadeOut(function(){
                        _this.render()
                    })
                    
                    Alert(response)
                }
                else{
                    $removeEl.setRemoveAnimation(false)
                    Alert(response)                    
                }
            }
        });
        
        return this
    }
    /**
     * @description will use a Item object to generate the HTML template for a cart list item
     * @param {SauceItem} item 
     * @returns Jquery Object referencing a new Item
     */
    createCartItem(item){
        return $(`
            <div class="cart-list-item border p-2 rounded" data-id="${item.id}">                                
                <div class="actions text-right mb-2" >
                    <button title="save item" data-id="${item.id}" class="save btn btn-success" style="display:none;">
                        <span class="fa fa-save"></span>
                        <span class="fa fa-spin fa-spinner" style="display:none;"></span>                    
                    </button>                  
                    <button title="remove item" data-id="${item.id}" class="delete btn btn-danger">
                        <span class="fa fa-trash"></span>
                        <span class="fa fa-spin fa-spinner" style="display:none;"></span>                    
                    </button>    
                </div>    
            <div class="pic text-center" >
                    <img style="max-height:130px" src="${BASE_PATH+'/public/'+item.img_path}" alt="${item.title} image"/>
                </div>
                <div class="content">
                    <h5 class="name text-center mt-3">${item.title}</h5>
                    <div class="qty-price qty ml-3 mt-3 text-center">
                        <span class="text-secondary font-weight-bold mr-1">Qty: </span>
                        <input min="1" type="number" max="100" step="1" value="${item.qty}"  data-id="${item.id}" />
                        <span class="price"> x $ ${item.price}</span>
                    </div>
                    <div class="charge ml-3 mt-4 mb-5 text-center">
                        <span class="text-secondary font-weight-bold">Charge: </span><span class="total">$ ${(item.price * item.qty).toFixed(2)}</span>
                    </div>
                </div>
                
                
            </div>
        `)
    }
    /**
     * @description will render the Shopping cart items to the DOM
     * @returns {ShoppingCart}
     */
    render(){
        $(this.cart+ ' .item-list').empty()
        if(this.items.length){
            for(let item of this.items){
                let $template = this.createCartItem(item)
                $(this.cart +' .item-list').append($template)
            }
            
            $(this.cart+ ' .totals .checkout').show()
            this.initCartListEventHandlers()
        }
        else{
            $(this.cart+ ' .item-list').append(`
                <p class="ml-2">No items to show</p>
            `)
            $(this.cart+ ' .totals .checkout').hide()
        }
        this.renderTotals()
        return this
    }
    /**
     * @description will render the totals part of the shopping cart to the DOM
     * @returns {ShoppingCart}
     */
    renderTotals(){
        
        let grossTotal = this.items.length ? this.items
                .map(item=>item.price * item.qty)
                .reduce((a,b) => a + b) : 0.00,
            tax = grossTotal * 0.05
        $(this.cart).find('.grossTotal .value').text(grossTotal.toFixed(2))
        $(this.cart).find('.tax .value').text(tax.toFixed(2))
        $(this.cart).find('.netTotal .value').text((grossTotal + tax).toFixed(2))
        return this
    }

    /**
     * @description Will initialize the event handlers for events that show, hide, clearing of the Shopping Cart
     */
    initEventHandlers(){
        $(this.cart+' #clearCart').click(e => {
            if (this.items.length == 0) return
            
            $('.delete').setRemoveAnimation(true)
            var _this = this;
            $.ajax({
                url: BASE_PATH+`/api/cart.php?cart_id=${_this.data.id}`,
                type: 'DELETE',
                contentType: 'application/json',
                success: function(response) {
                    if(response.status){
                        _this.items = []                        
                        _this.render()
                        Alert(response)   
                    }
                    else{
                        $('.delete').setRemoveAnimation(false)

                        Alert(response)                        
                    }
                }
            });
         
        })
    }
    /**
     * @description Initializes the event handlers for events that affect the dynamically added elements of the Shopping cart, such as the quantity and delete button of a shopping cart item
     */
    initCartListEventHandlers(){
        // initialize qty input change handler
        $('.cart-list-item .qty input').on('change', function(e){
            let $this = $(e.currentTarget),
                qty = parseInt($this.val())
            
            if(qty < 1 || isNaN(qty)) qty = 1
            else if (qty > 100) qty = 100
            
            $this.val(qty)

            let itemIndex = this.getItemIndexById(parseInt($this.attr('data-id')))
            this.items[itemIndex].qty = qty
            
            let total = (this.items[itemIndex].qty * this.items[itemIndex].price).toFixed(2)
            this.items[itemIndex].item_total = total
            $this.parent().parent().find('.total').text('$ '+total)

            this.renderTotals()

            $('.save[data-id="'+this.items[itemIndex].id+'"]').show()
           

        }.bind(this))
        // on delete button clicked event handler
        $('.cart-list-item .actions .delete').click(function(e){
            let $this = $(e.currentTarget),
                itemId = $this.attr('data-id')
            this.removeItemFromCart(parseInt(itemId), $this)
        }.bind(this))
        $('.cart-list-item .actions .save').click(function(e){
            let $this = $(e.currentTarget)
            let itemIndex = this.getItemIndexById(parseInt($this.attr('data-id')))
            let item = this.items[itemIndex]
            this.saveItem(item, $this)
            return item
        }.bind(this))

    }

    saveItem(item, $el){
        $el.setSaveAnimation(true)
        console.log(item)
        $.ajax({
            url: BASE_PATH+`/api/cart.php?cart_id=${this.data.id}&item_id=${item.id}&qty=${item.qty}&charge=${item.item_total}`,
            type: 'PUT',
            contentType: 'application/json',
            
            success: function(response) {
                if(response.status){
                    $el.setSaveAnimation(false)
                    $el.hide()                    
                }
                else{
                    $el.setSaveAnimation(false)
                }
                Alert(response)                    
            }
        });
    }


}