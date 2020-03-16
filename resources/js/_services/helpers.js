export const prezziFormatter = (n) => {
    if (isNaN(n)) {
        console.warn("Il valore da formattare non è un numero", n);
        return n
    }
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EUR' }).format(n);
}

const ordiniStato = (stato) => {

    let i;

    switch (stato) {
        case "APERTO":
            i = ({
                raw: stato,
                label: "In attesa di pagamento.",
                waiting: false,
                description: "",
                variant: "warning",
                colorClass: "text-warning",
            });
            break;

        case "ELABORAZIONE":
            i = ({
                raw: stato,
                label: "Pagamento in elaborazione",
                waiting: true,
                description: "",
                variant: "success",
                colorClass: "text-success",
            });
            break;

        case "PAGATO":
            i = ({
                raw: stato,
                label: "Pagato",
                waiting: true,
                description: "A breve verranno generati i tickets.",
                variant: "success",
                colorClass: "text-success",
            });
            break;

        case "ELABORATO":
            i = ({
                raw: stato,
                label: "Elaborato",
                waiting: false,
                description: "",
                variant: "success",
                colorClass: "text-success",
            });
            break;

        case "CHIUSO":
            i = ({
                raw: stato,
                label: "Chiuso",
                waiting: false,
                description: "",
                variant: "primary",
                colorClass: "text-success",
            });
            break;


        case "RIMBORSATO":
            i = ({
                raw: stato,
                label: "Rimborsato",
                waiting: false,
                description: "",
                variant: "success",
                colorClass: "text-success",
            });
            break;

        default:
            stato && console.warn(stato)
            i = ({
                raw: stato,
                label: stato,
                waiting: false,
                description: "",
                variant: stato,
                colorClass: stato,
            });
            break;
    }

    return i;
}
const ruoli = [
    {
        raw: "admin",
        label: "Admin",
    },
    {
        raw: "account_manager",
        label: "Account manager",
    },
    {
        raw: "cliente",
        label: "Cliente",
    },
    {
        raw: "fornitore",
        label: "Fornitore",
    },
]

const utenteRuoloRaw = (label) => {
    let l = _.find(ruoli, { label })

    return l && l.raw
}
const utenteRuoloLabel = (raw) => {
    let l = _.find(ruoli, { raw })

    return l && l.label
}
const Helpers = {
    ordini: {
        stato: ordiniStato,
    },
    prezzi: {
        formatter: prezziFormatter,
    },
    utenti: {
        ruoli: {
            get: () => ruoli,
            getRaw: utenteRuoloRaw,
            getLabel: utenteRuoloLabel,
            getByRaw: (raw) => _.find(ruoli, { raw }),
        },
    },
}
export default Helpers 