export const prezziFormatter = new Intl.NumberFormat('en-US', { style : 'currency' , currency: 'EUR' } ).format;

const ordiniStato = ( stato ) => {

    let i;
    
    switch (stato) {
        case "APERTO":
            i = ({
                raw: stato,
                label : "In attesa di pagamento.",
                variant : "warning",
                colorClass : "text-warning"
            });
            break;

        case "ELABORAZIONE":
            i = ({
                raw: stato,
                label : "Pagamento in elaborazione",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "PAGATO":
            i = ({
                raw: stato,
                label : "Pagato.",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "EROGATO":
            i = ({
                raw: stato,
                label : "Erogato.",
                variant : "success",
                colorClass : "text-success"
            });
            break;

        case "CHIUSO":
            i = ({
                raw: stato,
                label : "Chiuso",
                variant : "primary",
                colorClass : "text-success"
            });
            break;
    

        case "RIMBORSATO":
            i = ({
                raw: stato,
                label : "Rimborsato",
                variant : "success",
                colorClass : "text-success"
            });
            break;
    
        default:
            console.log(stato)
            i = ({
                raw: stato,
                label : stato,
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