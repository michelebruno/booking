/* eslint-disable react/display-name */
import React , { useState } from "react"
import { Link } from "react-router-dom"
import PropTypes from 'prop-types'

import Button from 'react-bootstrap/Button';
import MUIDataTable from "mui-datatables";

import AxiosConfirmModal from '../components/AxiosConfirmModal';
import Helpers from "../_services/helpers";
import useServerSideCollection from "../_services/useServerSideCollection";

const ServiziTabella = ( {  title, esercente, editable, url  , ...props } ) => {
    
    const [ collection, serverSideOptions , { reload : reloadApi } ] = useServerSideCollection( url )

    const servizi = collection && collection.data

    const readOnly = typeof props.readOnly === "undefined" ? ! editable : props.readOnly

    const DeleteServizioButton = props => {

        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-trash" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="delete" onHide={() => { setShow(false); reloadApi()}} title="Conferma" >
                Sei sicuro di cancellare questo servizio?
            </AxiosConfirmModal>

        </div>
    }
    
    const RestoreServizioButton = props => {

        const [show, setShow] = useState(false)

        return <div className={props.className}>
        
            <Button variant="danger" className="ml-1" onClick={ () => setShow(true) }>
                <i className="fas fa-undo" />
            </Button>

            <AxiosConfirmModal url={ props.url } show={show} method="patch" onHide={() => { setShow(false); reloadApi()}} title="Conferma" >
                Sei sicuro di voler ripristinare questo servizio?
            </AxiosConfirmModal>
        </div>
    }

    const actionButtons = ( _cell , { rowIndex } ) => {
        const row = servizi[rowIndex]
        return <>
            <Button as={ Link } to={ { pathname : row._links.self , state : { servizio : row , esercente : esercente }}} variant="success">
                { readOnly || row.cestinato ? <i className="fas fa-eye" /> : <i className="fas fa-edit" />   }
            </Button>
            { ! readOnly && !row.cestinato && <DeleteServizioButton url={ row._links.self } className="d-none d-md-inline-block" />}
            { ! readOnly && row.cestinato && <RestoreServizioButton url={ row._links.restore } method="patch" className="d-none d-md-inline-block" />}
        </>
    }

    let columns
    return <MUIDataTable 
        title={title}
        data={ servizi }
        columns={ columns = [
            {
                name: 'stato',
                label : " ",
                options : {
                    download : false,
                    customBodyRender :  ( _cell , row ) => {
                        if ( row.cestinato ) return <i className="fas fa-circle text-dark fa-lg" title="Cestinato" />
        
                        switch (row.stato) {
                            case "pubblico":
                                return <i className="fas fa-circle text-success fa-lg" title="Pubblico" />
                                // eslint-disable-next-line no-unreachable
                                break;
                        
                            case "privato":
                                return <i className="fas fa-circle text-secondary fa-lg" title="Privato" />
                                // eslint-disable-next-line no-unreachable
                                break;
                        }
                    }
                } 
            },
            {
                name : 'codice',
                label : 'Cod.'
            },
            {
                name : 'titolo',
                label : 'Titolo'
            },
            {
                name : 'tariffe.intero.imponibile',
                label : 'Imponibile (prezzo intero)',
                options : {
                    customBodyRender : v => Helpers.prezzi.formatter(v)
                }
            },
            {
                name : 'iva',
                label : 'IVA',
                options : {
                    customBodyRender : iva => iva + "%"
                }
            },
            {
                name : 'disponibili',
                label : 'Disponibilità',
            },
            {
                name : 'modifica',
                label : ' ',
                options : {
                    customBodyRender : actionButtons
                }
            }
        ] }
        options={{
            ...serverSideOptions( columns ), 
            print : false,
            selectableRows : editable ? "multiple" : "none"
        }}
        />
      
}
ServiziTabella.propTypes = {
    className : PropTypes.string,
    editable : PropTypes.bool,
    esercente : PropTypes.object.isRequired,
    readOnly : PropTypes.bool,
    servizi : PropTypes.array,
    title : PropTypes.string,
    url : PropTypes.string.isRequired
}
export default ServiziTabella