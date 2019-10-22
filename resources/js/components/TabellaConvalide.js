import React from 'react'

import { Table } from 'react-bootstrap'

export default ( { esercente, ...props } ) => {
    return(
        <Table hover>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Nome</th>
                <th>Deals associati</th>
                <th>Costo al pubblico</th>
                <th>Importo</th>
                <th>Fattura</th>
            </tr>
        </thead>
        <tbody>
            {
                [ 0, 1, 2, 3, 4, 5].map( ( value , index ) => {
                    return(
                        <tr key={index}>
                            <td>{ "ESF41" + value }</td>
                            <td>Pranzo da €{ 12 + value } </td>
                            <td>Pranzo tipico a Bologna</td>
                            <td>€15</td>
                            <td>€12</td>
                            <td><i className={"fas fa-circle " + ([0,1,5].includes(value) ? 'text-success' : 'text-danger' )} title={([0,1,5].includes(value) ? 'Pagato' : 'Da pagare' )} /> {"19/1" + ( value * 3 + 1 + value )}</td>
                        </tr>
                    )
                })
            }
        </tbody>
    </Table>
    )
}