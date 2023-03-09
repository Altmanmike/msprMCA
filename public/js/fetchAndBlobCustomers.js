'use strict'; 

// url de l'API MOOC pour obtenir les "customers" et toutes leurs données
//https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/8/orders/25/products

/*
async function fetchData() {
  try {
    const response = await fetch('https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers');
    const saveAPIarray = await response.json();
    console.log(saveAPIarray);
  } catch (error) {
    console.error(error);
  }
}

fetchData();*/

let dataMooc = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers";
let saveAPIarray = new Array();  

fetch(dataMooc)
    .then(response => {
        //console.log(response);
        return response.json();
    }).then(results => {        
        for(var i = 0; i < results.length; i++) {
            saveAPIarray.push(results[i]);     //on récupère le retour d'api en liste des customers
        }       
    });

console.log(saveAPIarray);

/************************************************* EXTRACTION JSON *************************************************/
let saveAPImooc = () => {
    // This variable stores all the data.
    //console.log(document.getElementById('eventoutput_SaveAPIJson'));
    let data =         
    ((document.getElementById('eventoutput_SaveAPIJson').innerText = JSON.stringify(saveAPIarray)));
    
    // Convert the text to BLOB.
    const textToBLOB = new Blob([data], { type: 'text/plain' });
    const sFileName = 'saveAPImooc.json';	   // The file to save the data.

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
}	