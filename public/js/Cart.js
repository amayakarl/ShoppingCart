var $cartLink = $('#shoppingCartShow').find('.fa-shopping-cart')
var $cartLinkCount = $('#shoppingCartItemCount')
var $cartLinkAnimation = $('#cartLoadingAnimation')
function updateCount(){
    let currentCount = $cartLinkCount.text()
    $cartLinkCount.text(parseInt(currentCount) + 1)
}
function addItemShowAnimation(){
    updateCount()
    $cartLinkCount.animate({
        fontSize:'30px',
        right: '47px',
        bottom: '68px',
        color:'white'
    }, 500, function(){
        $cartLinkCount.animate({
            fontSize:'12px',
            bottom:'58px',
            color:'#F81D1D',
            right:'50px'
        }, 500)
    })
}
function setCartLinkBusy(state){
    if(state){
        $cartLink.hide()
        $cartLinkCount.hide()
        $cartLinkAnimation.show()
    }
    else{
        $cartLink.show()
        $cartLinkCount.show()
        $cartLinkAnimation.hide()
    }
}
function isAlreadyInCart(sauce_id, cart_items){
    return cart_items.length > 0
        ? cart_items.filter(item => item.sauce_id == sauce_id).length > 0
        : false
}
function updateItemInStoreData(_store, item){
    _store.data = _store.data.map(_i =>{
        if(_i.id == item.id){
            _i.isInCart = true
        }
        return _i
    })
}
function handleAddNewItem(item, _this, $el){
    
    setCartLinkBusy(true)
    
    $('.curtain').show()
    $('.addToCart').attr('disabled','disabled')
    var data = {
        cart_id: $('#shoppingCartShow').attr('data-id'),
        sauce_id: item.id,
        item_total: item.price,
        sauce_title:item.title,
        qty:1
    }
    $.post(BASE_PATH+'/api/cart.php', data , function(response){
        if(response.status){           
            Alert(response)
            setCartLinkBusy(false)
            addItemShowAnimation()
            $('.addToCart').removeAttr('disabled')
            $('.curtain').hide()
            $el.remove()
            updateItemInStoreData(_this, item)
            $('#shoppingCartShow').attr('data-id', response.data.cart_id)      
        }
        else{
            setCartLinkBusy(false)
            $('.addToCart').removeAttr('disabled')
            $('.curtain').hide()
            
            Alert(response)
        }
    })
}
