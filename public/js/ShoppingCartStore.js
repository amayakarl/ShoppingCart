/**
 * Responsible for the managing the sauces on display in the page
 */
class ShoppingCartStore {
    /**
     * 
     * @param {string} selector the Jquery selector for the Store Item List
     * @param {Array.<SauceItem>} data the list of sauces to display on the store
     */
    constructor(selector, data){
        this.data = data
        this.selector = selector
    }

    // searches a store item by its id
    searchItemById(id){
        return this.data.filter(item=>item.id == id)[0] ?? null
    }
    // search method for the search input field found in the DOM
    searchByItemTitle(title){
        if(!title.trim().length) this.render()
        else{
            let newData = this.data.filter(item => (new RegExp(title.toLowerCase())).test(item.title.toLowerCase()))
            this.render(newData)
        }

        return this
    }
    // initializes the even handlers pertaining to the store items
    initEventHandlers() {
        var _this = this;
        $('button.addToCart').click((e)=>{
            let item = this.searchItemById($(e.currentTarget).attr('data-id'))
            handleAddNewItem(item, _this, $(e.currentTarget))
        })
        $('#ItemDetailsModal').on('show.bs.modal', (e)=>{
            let item = this.searchItemById($(e.relatedTarget).attr('data-id'))
            if(item){
                $(e.currentTarget).find('#ItemDetailsModalLabel').text(item.title)
                $(e.currentTarget).find('#description').text(item.description)
                $(e.currentTarget).find('#ingredients').text(item.ingredients)
                
                $(e.currentTarget).find('.price .value').text(item.price)
                $(e.currentTarget).find('#sauce-img').attr('src', BASE_PATH+'/public/'+item.img)
            }
        })
        
        return this
    }

    // gets a list of brands
    get brands(){
        return new Set(this.data.map(item => item.brand))
    }
    // gets a list of prices
    get prices(){
        return this.data.map(item => item.price)
    }
    // returns an object containing the min and max prices
    get priceMinMax(){
        return {
            min:Math.min(...this.prices),
            max:Math.max(...this.prices)
        }
    }
    // renders the store items to the DOM, if no items exist then "no items to show" is displayed on the DOM
    //  will clear any content within the target parent of the list of items
    //  can be provided a doctored list of store items, to be used by sort and filter methods
    render(doctoredData) {
        $(this.selector + ' .grid').empty()
        let currentData = doctoredData ?? this.data

        for (let item of currentData) {
            
            let template = `
                <div class="itemCard text-center" data-id="${item.id}">
                    <div class="text-right">
                        <span class="price">
                            <span class="prefix">$</span>
                            <span class="value">${item.price.toFixed(2)}</span>
                        </span>
                    </div>
                    <div class="image">
                        <img class="img-fluid" src="${BASE_PATH+'/public/'+item.img}" alt="${item.title} image">
                    </div>
                    <h5 class="mt-3 mb-5">${item.title}</h5>
                    <div class="container-fluid">
                        <div class="actions row">
                            <div class="col-6">
                                <button data-id="${item.id}" data-target="#ItemDetailsModal" data-toggle="modal" class="btn btn-danger rounded details action">Details</button>
                            </div>
                            <div class="col-6">
                            ${
                            !item.isInCart?
                             `<button class="addToCart action" data-id="${item.id}">
                                    <span class="fa fa-cart-plus"></span>
                                </button>`:''
                            }
                            </div>
                        </div>
                    </div>
                </div>
            `
            $(this.selector + ' .grid').append(template)
        }

        if(!currentData.length){
            $(this.selector + ' .grid').append(`
             <p>No items to show.</p>
            `)

            return this
        }
       
        this.initEventHandlers()

        return this
    }
    // will sort the data by brand, sorting depends on the brand sort state
    sortByBrands(brandsSortState){
        let sortFunc = ({
            'unsorted': data => data, //do nothing
            'asc': data => data.sort((a,b)=>a.brand < b.brand ? -1 : 0), // sort store items by brand in ascending order
            'dsc': data => data.sort((a,b)=>a.brand > b.brand ? -1 : 0) // sort store items by brand in descending order
        })[brandsSortState]

        return sortFunc([...this.data]) // create a copy of the data before sorting
    }
    // will sort the data by price, but will group by brand if the brand has a sort state other than unsorted
    sortByPrice(priceSortState, brandsSortState, data){
        
        if(brandsSortState != "unsorted"){
            let groupedByBrand = {}
            //first group the data by brand
            for(let item of data){
                if(groupedByBrand[item.brand]) 
                    groupedByBrand[item.brand].push(item)
                else groupedByBrand[item.brand] =  [ item ]
            }

            let newData = []
            // sort each group by price
            for(let group in groupedByBrand){
                if(priceSortState == 'asc')
                    groupedByBrand[group] = groupedByBrand[group].sort((a,b)=>a.price < b.price ? -1 : 0) //asc
                else 
                    groupedByBrand[group] = groupedByBrand[group].sort((a,b)=>a.price > b.price ? -1 : 0) //desc

                newData.push(...groupedByBrand[group]) // extract each group's array back into a single array
            }
            
            return newData
        }
        else{

            if(priceSortState == 'asc')
                data.sort((a,b) => a.price < b.price ? -1 : 0) //asc
            else 
                data.sort((a,b) => a.price > b.price ? -1 : 0) //desc
            return data
        }
    }
    // entry sort function, currently only supports sorting by brands and price
    sort({brands, price}){
        let data = this.sortByBrands(brands.state)

        if(price.state == "unsorted")
            this.render(data)
        else 
            data = this.sortByPrice( price.state, brands.state, data)

        this.render(data)

        return this
        
    }

}