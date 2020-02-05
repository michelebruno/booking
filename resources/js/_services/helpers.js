export const prezziFormatter = new Intl.NumberFormat('en-US', { style : 'currency' , currency: 'EUR' } ).format;

const ordiniStato = ( stato ) => {

    let i;
    
    switch (stato) {
        case "APERTO":
            i = ({
                raw: stato,
                label : "In attesa di pagamento.",
                description : "",
                variant : "warning",
                colorClass : "text-warning"
            });
            break;

        case "ELABORAZIONE":
            i = ({
                raw: stato,
                label : "Pagamento in elaborazione",
                description : "",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "PAGATO":
            i = ({
                raw: stato,
                label : "Pagato",
                description : "A breve verranno generati i tickets.",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "ELABORATO":
            i = ({
                raw: stato,
                label : "Elaborato",
                description : "",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "CHIUSO":
            i = ({
                raw: stato,
                label : "Chiuso",
                description : "",
                variant : "primary",
                colorClass : "text-success"
            });
            break;
    

        case "RIMBORSATO":
            i = ({
                raw: stato,
                label : "Rimborsato",
                description : "",
                variant : "success",
                colorClass : "text-success"
            });
            break;
    
        default:
            console.log(stato)
            i = ({
                raw: stato,
                label : stato,
                description : "",
                variant : stato,
                colorClass : stato
            });
            break;
    }

    return i;
} 

const Helpers = {
    ordini : {
        stato : ordiniStato
    },
    prezzi : {
        formatter : prezziFormatter
    }
}
export default Helpers 