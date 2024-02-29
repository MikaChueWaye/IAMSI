const DOLAPIKEY = `jVsEA99I1sFHEcm27Ib9ki1E1v4IpJc8`

//POST
// http://localhost:81/api/index.php/invoices
// Crée la facture brouillon
// retourne l'id de la facture créée
async function createInvoice(id) {
    let request = await fetch("http://localhost:81/api/index.php/invoices", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'DOLAPIKEY': DOLAPIKEY
        },
        body: JSON.stringify({
            "socid": id, //id de la parti tierce (le joueur. Par ex: ici id1 = bobo)
            "cond_reglement_id": "6", //condition de reglement "sur commande"
            "mode_reglement_id": "4" //mode de reglement par espece
        }),
    })
    return await request.json()
}

// POST
// http://localhost:81/api/index.php/invoices/14/lines
// Ajoute à la facture ID: 14, un produit (ici désigné en tant que "ligne")
async function addProductsToInvoice(invoiceId, subprice, qty, fk_product) {
    let request = await fetch("http://localhost:81/api/index.php/invoices/"+invoiceId+"/lines", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'DOLAPIKEY': DOLAPIKEY
        },
        body: JSON.stringify({
            "subprice": subprice, //prix du produit ex: "15.00000000"
            "qty": qty, //qtte ex: "10"
            "fk_product": fk_product, //id du produit ex: "50"
            "rang": "-1" //rang de la ligne (pour l'ordonner, ne pas changer)
        }),
    })
    return request.json()
}


//POST
// http://localhost:81/api/index.php/invoices/14/validate
// Valide la facture brouillon
async function validateInvoice(id) {
    let request = await fetch("http://localhost:81/api/index.php/invoices/"+id+"/validate", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'DOLAPIKEY': DOLAPIKEY
        }
    })
    return request.json()
}

//POST
// http://localhost:81/api/index.php/invoices/paymentsdistributed
// Effectue le paiement et ferme la facture
async function payAndCloseInvoice(invoiceId, amount) {
    let request = await fetch("http://localhost:81/api/index.php/invoices/paymentsdistributed", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'DOLAPIKEY': DOLAPIKEY
        },
        body: JSON.stringify({
            "arrayofamounts": {[invoiceId]:{"amount":amount, "multicurrency_amount": ""}}, //id de la facture (18), qtte d'argent ex: "arrayofamounts": {"18":{"amount":"144", "multicurrency_amount": ""}}
            "datepaye": ""+ Math.round(Date.now()/1000), //date de paiement en Unix ex: "1701449506"
            "paymentid": 4, // paiement par espèce
            "closepaidinvoices": "yes", //???
            "accountid": 1 //???
        }),
    })
    return request.json()
}

//GET
// http://localhost:81/api/index.php/documents/download?modulepart=facture&original_file=IN2312-0001%2FIN2312-0001.pdf
// Enregistre la facture avec pour original_file: ref_de_la_facture/ref_de_la_facture.pdf
// année+mois+ine facture
function getFacture(ref) {
    let url = "http://localhost:81/api/index.php/documents/download?modulepart=facture&original_file="+ref+"%2F"+ref+".pdf"
    fetch(url, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json',
            'DOLAPIKEY': DOLAPIKEY
        }})
        .then(html_response => html_response.json())
        .then(data => downloadPDF(data))
}

// Function to convert base64 to Blob
function base64ToBlob(base64, contentType) {
    const byteCharacters = atob(base64);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length; offset += 512) {
        const slice = byteCharacters.slice(offset, offset + 512);
        const byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    const blob = new Blob(byteArrays, { type: contentType });
    return blob;
}

// Download function
function downloadPDF(jsonData) {
    const blob = base64ToBlob(jsonData.content, jsonData["content-type"]);
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = jsonData.filename;
    document.body.appendChild(link); // Required for Firefox
    link.click();
    document.body.removeChild(link);
}

function makeFacture(subprice, qty, fk_product) {
    createInvoice(1)
        .then(invoiceId => {
            console.log("id facture:")
            console.log(invoiceId)
            addProductsToInvoice(invoiceId, subprice, qty, fk_product)
                .then(response => {
                    console.log("ajout de produits :")
                    console.log(response)
                    validateInvoice(invoiceId)
                        .then(validated => {
                            console.log("validation de la facture:")
                            console.log(validated)
                            payAndCloseInvoice(invoiceId, validated.multicurrency_total_ttc)
                                .then(payed => {
                                    console.log("paiment final de la facture:")
                                    console.log(payed)
                                    getFacture(validated.ref)
                                })
                        })
                })
        })
}

function test() {
    // ajouter une boucle for pour chaque produit
    let subprice = "15.00000000"
    let qty = "10"
    let fk_product = "1"
    makeFacture(subprice, qty, fk_product)
}