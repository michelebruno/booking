import React, { useState, useEffect } from "react"

import PropTypes from 'prop-types'


import { isInvalid } from "../_services/formValidation";
import Button from '@material-ui/core/Button';
import TextField from '@material-ui/core/TextField';
import InputAdornment from '@material-ui/core/InputAdornment';
import MenuItem from '@material-ui/core/MenuItem';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Switch from '@material-ui/core/Switch';


import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogTitle from '@material-ui/core/DialogTitle';


const DialogDealForm = ({ open, onClose, url, onSuccess, inputProps }) => {

    const [api, setApi] = useState()

    const [state, setState] = useState()

    const sending = state === "sending"
    const error = state === "error"
    const created = state === "created"

    const errors = error && api && api.errors

    const stati = [
        {
            value: "pubblico",
            label: "Pubblico",
        },
        {
            value: "privato",
            label: "Privato ",
        },
    ]

    const [data, setData] = useState({
        titolo: "",
        descrizione: "",
        stato: "",
        codice: "",
        iva: 22,
        disponibili: 0,
    })
    const [customCod, setCustomCod] = useState(true)
    /* 
        if (modIVAinc) {
            data.tariffe = {
                intero: {
                    importo: Number.parseFloat(prezzoII).toFixed(2)
                }
            }
        } else data.tariffe = {
            intero: {
                imponibile: Number.parseFloat(prezzo).toFixed(2)
            }
        } 
    */


    const fieldProps = field => {

        let props = {
            value: data[field],
            onChange: e => setData({ ...data, [field]: e.target.value }),
            variant: "outlined",
            margin: "normal",
        }

        props.fullWidth = true

        if (sending || created) {
            props.readOnly = true
        } else if (error && errors) {

            props.helperText = errors[field]
            props.error = isInvalid(errors, field)
        }

        return { ...props, inputProps }
    }

    useEffect(() => {

        if (!sending) return;

        const source = axios.CancelToken.source()

        axios({
            method: 'POST',
            url: url || '/deals',
            data,
            cancelToken: source.token,
        })
            .then(res => {
                setApi(res.data)
                setState("created")
                onSuccess(res.data)
            })
            .catch(error => {
                if (axios.isCancel(error)) return;

                setState()

                if (error.response) {
                    if (error.response.status == 422) {
                        setState("error")
                        setApi(error.response.data)
                    }
                    else console.warn(error.response)
                }

            })
        return source.cancel
    }, [data, onSuccess, sending, state, url])

    const handleSubmit = (e) => {

        e.preventDefault()

        setState("sending")

    }

    /**
        if (modIVAinc) {
            data.tariffe = {
                intero: {
                    importo: Number.parseFloat(prezzoII).toFixed(2)
                }
            }
        } else data.tariffe = {
            intero: {
                imponibile: Number.parseFloat(prezzo).toFixed(2)
            }
        } 
     */

    return <Dialog open={open} onClose={onClose} scroll="body"  >
        <form onSubmit={handleSubmit} className={"form-nuovo-prodotto "} >
            <DialogTitle>
                Crea un nuovo Deal
        </DialogTitle>
            <DialogContent>


                {error && api && api.message && api.message}
                {created && api ? <div>Prodotto creato con codice {api.codice}.</div> : <>
                    {!customCod &&
                        <TextField
                            label="Codice"
                            required
                            {...fieldProps("codice")}
                        />
                    }
                    <FormControlLabel
                        control={
                            <Switch
                                checked={customCod}
                                color="primary"
                                onChange={() => {
                                    if (customCod) {
                                        setData({ ...data, codice: "" })
                                    }
                                    setCustomCod(!customCod)
                                }}
                            />}
                        label="Codice autoassegnato"
                    />
                </>}

                <TextField
                    label="Titolo"
                    required
                    {...fieldProps("titolo")}
                />
                <TextField
                    label="Descrizione"
                    multiline
                    rows="4"
                    fullWidth
                    {...fieldProps("descrizione")}
                />

                <TextField
                    label="Stato"
                    select
                    required
                    SelectProps={{
                        style: {
                            minWidth: "8em",
                        },
                    }}
                    {...fieldProps("stato")}
                >
                    {stati.map(stato => <MenuItem value={stato.value} key={stato.value} >
                        {stato.label}
                    </MenuItem>)}
                </TextField>

                <TextField
                    label="Importo"
                    type="number"
                    min="0"
                    multiline
                    required
                    InputProps={{
                        startAdornment: <InputAdornment position="start">€</InputAdornment>,
                    }}
                    helperText="Iva inclusa"
                    {...fieldProps("importo")}
                />

                <TextField
                    label="Iva"
                    type="number"
                    required
                    max="99"
                    min="0"
                    InputProps={{
                        inputProps: {
                            max: "99",
                            min: "0",
                            size: 5,
                        },
                        endAdornment: <InputAdornment position="end">%</InputAdornment>,
                    }}
                    {...fieldProps("iva")}
                />

                <TextField
                    label="Disponibili"
                    type="number"
                    required
                    InputProps={{
                        inputProps: {
                            min: 0,
                            size: 5,
                        },
                    }}
                    {...fieldProps("disponibili")}
                />

            </DialogContent>
            <DialogActions>
                <Button variant="text" color="default" disabled={sending || created} onClick={onClose} >Annulla</Button>
                <Button variant="text" type="submit" color="primary" disabled={sending || created} >Invia</Button>
            </DialogActions>
        </form >
    </Dialog>
}

DialogDealForm.defaultProps = {
    xs: 12,
    onSuccess: () => undefined,
    spacing: 5,
}

DialogDealForm.propTypes = {
    inputProps: PropTypes.object,
    url: PropTypes.string,
    onSuccess: PropTypes.func,
    onClose: PropTypes.func,
    open: PropTypes.bool,
    xs: PropTypes.number,
    sm: PropTypes.number,
    md: PropTypes.number,
    lg: PropTypes.number,
    xl: PropTypes.number,
}

export default DialogDealForm;