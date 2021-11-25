const fs = require('fs')
const dataFile = 'data.sql'


function getCode(length=10) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
async function main(){
    let data = fs.readFileSync(__dirname+'/js/placeHolderData.json')
    data = JSON.parse(data.toString())
    let dataFileContent = `insert into sauce(brand, title, price, description, img_path, ingredients) values `
    for(let sauce of data.PlaceHolderData){
        dataFileContent += `('${sauce.brand}', '${sauce.title}', ${sauce.price}, '${sauce.description}','${sauce.img}', '${sauce.ingredients}')${sauce.id == 12?';':','} \n`
    }
    

    process.stdout.write('\n[+] generated sauce table insert statements...\n')

    
    dataFileContent += '\n\n insert into promocodes(code, value, is_used) values '
    for(let i = 0; i<10; i++){
        dataFileContent += `('${getCode()}', 5.00, 0) ${i == 9?';':','}\n`
    }


    fs.writeFileSync(dataFile, dataFileContent)
    process.stdout.write('\n[+] generated promocode table insert statements...\n')
}
main()