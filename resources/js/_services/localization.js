
const MUIDatatableLabels = {
    body: {
        noMatch: "Nessun risultato.",
        toolTip: "Ordina",
        columnHeaderTooltip: column => `Ordina per ${ column.label.toLowerCase(  )}`
    },
    pagination: {
        next: "Pagina successiva",
        previous: "Pagina precedente",
        rowsPerPage: "Righe per pagina:",
        displayRows: "di",
    },
    toolbar: {
        search: "Cerca",
        downloadCsv: "Scarica CSV",
        print: "Stampa",
        viewColumns: "Colonne",
        filterTable: "Filtra risultati",
    },
    filter: {
        all: "Tutti",
        title: "Filtri",
        reset: "Azzera",
    },
    viewColumns: {
        title: "Mostra colonne",
        titleAria: "Mostra/Nascondi colonne",
    },
    selectedRows: {
        text: "righe selezionate",
        delete: "Elimina",
        deleteAria: "Elimina righe selezionate",
    },
}

const it = {
    MUIDatatableLabels
}

const localization = {
    it 
}
export default localization