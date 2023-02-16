/*var api = fetch("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers")
            .then(response => response.json())
            //.then(response => alert(JSON.stringify(response)))
            .then(response => JSON.stringify(response))
            //.catch(error => alert("Erreur : " + error));
*/

// Donc, créer un code qui récupère le fichier customers.json pour garder la bdd à jour, tous les ? périodiquement donc...

//var api = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers"; faux hihi
/*
var data = "text/json;charset=utf-8," + encodeURIComponent(api);

var a = document.createElement('a');
a.href = 'data:' + data;
a.download = 'data.json';
a.innerHTML = 'download customers.json';

var container = document.getElementById('container');
container.appendChild(a);
*/

/************** METHODE A VOIR DANS PHP AVEC INSERT ***********/
var api = fetch("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers")
            .then(response => response.json()) 
            //.then(response => alert(JSON.stringify(response[0])))           
            .then(response => console.log(JSON.stringify(response[0])))
            //.then(response => JSON.stringify(response))

            // renvoie 
            /*
            {
                "createdAt":"2022-07-10T18:54:04.554Z",
                "name":"Mr. Glenn Harvey",
                "username":"Jordyn.Volkman",
                "firstName":"Treva",
                "lastName":"Medhurst",
                "address":{
                    "postalCode":"54312",
                    "city":"Watsicatown"
                },
                "profile":{
                    "firstName":"Guiseppe",
                    "lastName":"Boehm"
                },
                "company":{
                    "companyName":"Dooley Group"
                },
                "id":"2",
                "email":"KHADIJA@gmail.com",
                "orders":[
                    {"createdAt":"2022-07-12T03:45:56.167Z","id":"2","customerId":"2"},{"createdAt":"2023-01-23T19:48:12.609Z","id":"71","customerId":"2","title":"first post"},{"createdAt":"2023-01-23T14:41:56.514Z","id":"72","customerId":"2","title":"first post"}
                ]
            }
            */