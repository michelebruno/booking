import React , { useState , useEffect } from 'react'
import { Card , Button } from 'react-bootstrap'  
import BootstrapTable from 'react-bootstrap-table-next'
import { Redirect , Link } from 'react-router-dom'

const Deals = ( props ) => {
    const [ redirectTo, setRedirectTo ] = React.useState(false);

    const [ deals , setDeals ] = useState()

    useEffect(() => {
        const source = axios.CancelToken.source()

        axios.get("/deals", { cancelToken : source.token })
            .then( res => setDeals(res.data))
            .catch( error => axios.isCancel(error) || console.error( error ))
        return () => {
            source.cancel()
        };

    }, [])
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
                    { deals && <BootstrapTable 
                        keyField="id"
                        noDataIndication="Non ci sono prodotti collegati."
                        data={deals}
                        columns={[
                            {
                                text: 'Cod.',
                                dataField: 'codice'
                            },
                            {
                                text: 'Titolo',
                                dataField: 'titolo'
                            },
                            {
                                text: 'Imponibile',
                                dataField: 'tariffe.intero.imponibile'
                            },
                            {
                                text: 'Disponibiiltà',
                                dataField: 'disponibili'
                            },
                            {
                                text : "",
                                dataField: "azioni",
                                formatter : ( cell, row ) =>{
                                    const Buttons = ( props ) => {

                                        let url = row._links.self
                                        let state = { deal : row }

                                        
                                        return <>
                                            <Button as={ Link } to={ { pathname: row._links.self , state: state} } variant="primary" className="mr-1" title="Accedi alla pagina del prodotto" ><i className="fas fa-edit" /></Button>
                                        </>

                                    }

                                    return <Buttons />
                                }
                            }
                        ]}
                        hover
                        bordered={ false }
                        />}
                </Card.Body>
            </Card>
        </React.Fragment> 
    )
}

export default Deals;