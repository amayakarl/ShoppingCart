/**
 * @author Karl Amaya
 * @description used for managing client side local storage and maintaining state at page reload
 */
class AppDB {
    
    constructor(name='testAPP'+(new Date().toISOString())){
        if(!localStorage.getItem(name)){ // only create the instance if it does not already exist
            localStorage.setItem(name, JSON.stringify({/*empty object*/ }))
        }
        this.name = name
    }
    /**
     * 
     * @param {String} key 
     * @param {object|String} value 
     * @returns {AppDB}
     * @description used to update the value of a key, will add a new entry to the database if the key does not yet exist
     */
    set(key, value){
        if(typeof key == 'undefined' && typeof value == 'undefined'){
            throw new Error('AppDB.set: key and value parameters cannot be undefined')
        }
        else if(key.trim().length == 0 )
            throw new Error('AppDB.set: key cannot be a blank string')
        
        let currentDB = JSON.parse(localStorage.getItem(this.name))
        currentDB[key] = value
        localStorage.setItem(this.name, JSON.stringify(currentDB))

        return this
       
    }
    /**
     * 
     * @param {String} key 
     * @returns {*}
     * @description gets the value of the key provided
     * 
     */
    get(key){
        if(typeof key != "string") throw new Error('AppDB.get: key is not a string')

        let currentDB = JSON.parse(localStorage.getItem(this.name))
        
        if(typeof currentDB[key] == 'undefined'){
            throw new Error('AppDB.get: '+key+' does not have any data or has not been set.')
        }
        return currentDB[key]
    }
    /**
     * @param {string} key
     * @returns {boolean}
     */
    resetKey(key){
        if(typeof key != "string"){
            throw new Error('AppDB.resetKey: key is not a string')
        }
        
        let currentDB = JSON.parse(localStorage.getItem(this.name))
        
        if(currentDB[key]){            
            delete currentDB[key]            
            localStorage.setItem(this.name, JSON.stringify(currentDB))
            return true
        }
        return true // since the key does not exist, then nothing happens
    }
    /**
     * @returns {AppDB}
     * @description sets the database back to an empty object
     */
    resetDB(){
        localStorage.setItem(this.name, '{}')
    }
}

const appDB = new AppDB('tongueSpice__'+$('meta[name="app-data-id"]').attr('content'))