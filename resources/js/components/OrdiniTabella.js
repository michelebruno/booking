/* eslint-disable react/prop-types */
import React, { useRef } from 'react'
import { Link } from 'react-router-dom';

import IconButton from '@material-ui/core/IconButton';
import VisibilityIcon from '@material-ui/icons/Visibility'

import Helpers, { prezziFormatter } from '../_services/helpers';
import ServerDataTable from './ServerDataTable';

const OrdiniTabella = ({ url, defaultFilter }) => {

    const ref = useRef()

    return <ServerDataTable
        ref={ref}
        url={url}
        columns={[
            {
                name: "stato",
                label: "Stato",
                options: {
                    sort: false,
                    filterOptions: {
                        names: ["Aperti", "Pagati"],
                    },
                    customBodyRender: cell => {

                        const stato = Helpers.ordini.stato(cell);

                        let className = "fas fa-circle ";

                        // eslint-disable-next-line react/prop-types
                        if (stato.waiting) {
                            className = "fas fa-spinner fa-spin "
                        }

                        // eslint-disable-next-line react/prop-types
                        return <i className={className + stato.colorClass} />
                    },
                },
            },
            {
                name: 'id',
                label: "#",
                _filterName: "id",
                options: {
                    filter: false,
                },
            },
            {
                name: 'cliente.email',
                label: "Cliente",
                options: {
                    sort: false,
                    filter: false,
                },
            },
            {
                name: "voci",
                label: "Prodotti",
                options: {
                    sort: false,
                    filter: false,
                    customBodyRender: voce => voce.length == 0 ? "-" : voce.map(v => {
                        if (v.descrizione && v.descrizione !== null) return v.descrizione
                    })
                        .filter(v => { if (v) return true; })
                        .join(', '),
                },
            },
            {
                name: "created_at",
                label: "Data",
                options: {
                    filter: false,
                },
            },
            {
                name: "importo",
                label: "Totale",
                options: {
                    filter: false,
                    customBodyRender: (value) => prezziFormatter(value),
                },
            },
            {
                name: "imponibile",
                label: "Imponibile",
                options: {
                    filter: false,
                    display: "false",
                    customBodyRender: (value) => prezziFormatter(value),
                },
            },
            {
                name: "_links",
                label: " ",
                options: {
                    sort: false,
                    filter: false,
                    download: false,
                    print: false,
                    customBodyRender: (cell, { rowIndex }) => <>
                        <IconButton size="small" component={Link} to={{ pathname: cell.self, state: { ordine: ref.current.getRow(rowIndex) } }} >
                            <VisibilityIcon />
                        </IconButton>
                    </>,
                },
            },
        ]}
    />

}

OrdiniTabella.defaultProps = {
    url: "/ordini",
    defaultFilter: { stato: ["Pagati"] },
}
export default OrdiniTabella;