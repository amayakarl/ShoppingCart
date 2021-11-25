/**
 * @description Shows an alert on the bottom right of the screen, the alert contains a message and an icon.
 * @param {{status:boolean, message:string}} options  options.status will control whether the alert is a danger (false) alert or a success (true) alert, options.message will hold the message to show in the Alert
 * @param {number} zIndex controls the z-index css value, the parameter is optional and by default will use z-index:999
 * 
 */

function Alert({status, message}, zIndex=9997){
    $('.s_alert').remove()
    let iconTemplate = `<span class="fa ${status? 'fa-check-circle': 'fa-ban'} ml-5"`
    let $template = $(`
        <div class="s_alert bg-${status?'success':'red'} text-white" style="z-index:${zIndex}">
            ${message}
            <div class="float-right">
            ${iconTemplate}
            </div>
        </div>
    `)
    $template.click(function(e){
        $(this).remove()
    })
    $('body').append($template)
    
    $template.animate({
        right:'20px'
    }, 300)

    setTimeout(function(){
        $template.animate({
            right:'-255px'
        }, 300 , function(){
            $template.remove()
        })
    },3000)
    
}