import React , { useState } from 'react'
import { Card, Table } from 'react-bootstrap'  
import BootstrapTable from 'react-bootstrap-table-next'
import { Redirect } from 'react-router-dom'

const Deals = ( props ) => {
    const [ redirectTo, setRedirectTo ] = React.useState(false);

    const [api, setApi] = useState({ willBeReloaded : true })

    const colonne = [
        {
            dataField: 'stato',
            text: '',
            headerStyle: {
                width: '4em'
            },
            formatter: ( cell ) => formattaStato(cell)
        },
        {
            dataField: 'id',
            text: 'ID',
            headerStyle: {
                width: '12em'
            }
        },
        {
            dataField: 'titolo',
            text: 'Titolo'
        },
        {
            dataField: 'disponibilità',
            text: 'Disponibilità',
            headerStyle: {
                width: '12em'
            }
        },
        {
            dataField: 'collegati',
            text: 'Servizi collegati',
            headerStyle: {
                width: '12em'
            }
        }
    ]

    /* Formattatori */

    const formattaStato = ( cell, row, rowIndex ) => {
        let color;

        switch (cell) {
            case 'abilitato':
                color = 'text-success'
                break;

            default:
                break;
        }

        return(
            <i title={cell} className={ "fa fa-circle " + color } ></i>
        )
    }


    const rowEvents = {
        onDoubleClick : ( e , row , rowIndex ) => {
            setRedirectTo('/deals/' + row.id);
        }
    }

    return(
        <React.Fragment>
            { redirectTo && <Redirect push to={ redirectTo } />} 
            <Card>
                <Card.Body> 
                    <h1>Deals</h1>
                    <p> {"<!--Azioni di filtraggio varie -->"}</p>
                    { api.data && <BootstrapTable
                        style={ { 'table-layout' : 'auto' } }
                        hover
                        keyField='id'
                        data={ api.data }
                        columns={ colonne }
                        rowEvents={ rowEvents }
                        bordered={ false }
                    />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;