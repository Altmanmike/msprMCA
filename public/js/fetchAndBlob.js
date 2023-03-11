'use strict'; 

// Code à exécuter ici        
/************************************************* CODE GET API(s) *********************************************************/
let dataMooc = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers";
let customers = new Array(); 

fetch(dataMooc)
    .then(response => {
        //console.log(response);            
        return response.json();
    }).then(results => {  

        //console.log(results);
        for(var i = 0; i < results.length; i++) {
            customers.push(results[i]);     //on récupère le retour d'api en liste en variable array 
        }      
    }); 

//console.log(customers);

//let dataMoocProducts = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/".idCustomer."/orders/".idOrder."/products"; 
let products = new Array(); 
let productsURL = new Array(); 
let resultsProductsURL = new Array();

fetch('/js/customers.json')
    .then(responseCustomers => {
        //console.log(responseCustomers);            
        return responseCustomers.json();
    }).then(async resultCustomers => { 

        for(var j = 0; j < resultCustomers.length; j++) {   

            //console.log(resultCustomers[j].orders);
            for(var k = 0; k < resultCustomers[j].orders.length; k++) {
                //console.log(resultCustomers[j].orders[k].customerId, resultCustomers[j].orders[k].id); // fonctionne
                let dataMoocProducts = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/"+resultCustomers[j].orders[k].customerId+"/orders/"+resultCustomers[j].orders[k].id+"/products"; 
                //console.log(dataMoocProducts);
                productsURL.push(dataMoocProducts);  
                //console.log(productsURL.length);   // marche bien           
            }
        }
        //var productsURL2 = productsURL;
        for(var l = 0; l < productsURL.length; l++) {
            sleep(750);
            await fetch(productsURL[l])
                .then(responseProductsURL => {
                    //console.log(responseProductsURL);            
                    return responseProductsURL.json();    //return responseProductsURL.json(); faux car url                    
                }).then(resultsProductsURL => {         
                    console.log(resultsProductsURL);
                    //console.log(resultsProductsURL.length);
                    for(var m = 0; m < resultsProductsURL.length; m++) {
                        //console.log(resultsProductsURL[m]);                        
                        products.push(resultsProductsURL[m]);     //on récupère le retour d'api en liste en variable array 
                    } 
                });
        }
        //console.log(products);    // parfait!!!!
        window.alert("Terminé! Vous pouvez télécharger customers.json et products.json"); 
    }); 

/************************************************* FUNCTION *******************************************************/
function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
    currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}

/************************************************* EXTRACTION JSON *************************************************/
let saveAPICustomersMooc = () => {
    // This variable stores all the data.    
    let data =         
    ((document.getElementById('eventoutput_SaveAPICustomersJson').innerText = JSON.stringify(customers)));
    
    // Convert the text to BLOB.
    const textToBLOB = new Blob([data], { type: 'text/plain' });
    const sFileName = 'customers.json';	   // The file to save the data.

    let newLink = document.createElement("a");
    newLink.download = sFileName;

    if (window.webkitURL != null) {
        newLink.href = window.webkitURL.createObjectURL(textToBLOB);
    }
    else {
        newLink.href = window.URL.createObjectURL(textToBLOB);
        newLink.style.display = "none";
        document.body.appendChild(newLink);
    }     
    newLink.click();
    //URL.revokeObjectURL(url);
    //window.stop();
}	

let saveAPIProductsMooc = () => {
    // This variable stores all the data.    
    let data2 =         
    ((document.getElementById('eventoutput_SaveAPIProductsJson').innerText = JSON.stringify(products)));
    
    // Convert the text to BLOB.
    const textToBLOB = new Blob([data2], { type: 'text/plain' });
    const sFileName = 'products.json';	   // The file to save the data.

    let newLink = document.createElement("a");
    newLink.download = sFileName;

    if (window.webkitURL != null) {
        newLink.href = window.webkitURL.createObjectURL(textToBLOB);
    }
    else {
        newLink.href = window.URL.createObjectURL(textToBLOB);
        newLink.style.display = "none";
        document.body.appendChild(newLink);
    }     
    newLink.click(); 
    //URL.revokeObjectURL(url);
    //window.stop();
}






