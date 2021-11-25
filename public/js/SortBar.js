/**
 * @typedef {Object} SortOptionItem
 * @property {string} type
 * @property {string} name
 * @property {string} initialState
 */

/**
 * Responsible for the sort bar found at the top of the page under the header.
 */
class SortBar{
    /**
     * 
     * @param {string} selector Sort Bar Jquery Selector for rendering Sort Options
     * @param {Array.<SortOptionItem>} sortOptions list of sort option items
     * @param {function} onSortOptionClicked takes a callback function to use when a sort option item is clicked
     */
    constructor(selector, sortOptions, onSortOptionClicked){
        this.selector = selector
        this.renderSortOptions(sortOptions)
        this.sortOptionClickedCallBack = onSortOptionClicked
        this.sortOptions = sortOptions
    }
    /**
     * @description will return an icon for the 
     * @param {string} type the type of sort option icon to get
     * @returns {object} Icon options object
     */
    sortIcons(type){
        return ({
            'letter':{
                'unsorted':'fa-sort',
                'asc':'fa-sort-alpha-asc',
                'dsc':'fa-sort-alpha-desc'
            },
            'number':{
                'unsorted':'fa-sort',
                'asc':'fa-sort-numeric-asc',
                'dsc':'fa-sort-numeric-desc'
            }
        })[type]
    }
    /**
     * @description returns the next sort state provided the current sort state
     * @param {string} currentSortState provide the sort option's current sort state
     * @returns {string} will return the next sort state
     */
    getNextSortState(currentSortState){
        return ({'unsorted':'asc','asc':'dsc','dsc':'unsorted'})[currentSortState]
    }
    /**
     * 
     * @description will create an object containing the sort option's sort states to pass onto the sort button clicked callback
     * @returns {SortBar}
     */
    postClickAction(){        
        let data = {}
        
        for(let sortOption of this.sortOptions){            
            data[sortOption.name.toLowerCase()] = {
                state: $(`.${sortOption.name}`).attr('data-sort-state')
            }
        }
        this.sortOptionClickedCallBack(data)
        return this
    }
    /**
     * @description will render the sort options to the DOM and set their clicked event handler
     * @returns {SortBar}
     */
    renderSortOptions(sortOptions){
        for(let sortOption of sortOptions){
            let $template = $(`
                <button class="btn ${sortOption.name}" data-sort-type="${sortOption.type}" title="${sortOption.initialState}" data-sort-state="${sortOption.initialState}">
                            ${sortOption.name}
                            <span class="fa ${this.sortIcons(sortOption.type)[sortOption.initialState]}"></span>
                </button>
            `)

            $template.click(e => {
                let sortType = $(e.currentTarget).attr('data-sort-type'),
                    currentState = $(e.currentTarget).attr('data-sort-state'),
                    nextState = this.getNextSortState(currentState)


                $(e.currentTarget).attr('data-sort-state', nextState)
                    .attr('title',nextState)
                    .find('.fa')
                        .toggleClass(this.sortIcons(sortType)[currentState])
                        .toggleClass(this.sortIcons(sortType)[nextState])
                
                this.postClickAction()
            })
            $(this.selector).append($template)

        }

        // make sortBar stick to the top on scroll
        let stickyOffset = $(this.selector).offset().top

        $(window).scroll(function(){   
            scroll = $(window).scrollTop()

            if (scroll >= stickyOffset) 
                $(this.selector).addClass('fixedTop')
            else $(this.selector).removeClass('fixedTop')
        }.bind(this))

        return this
    }
}