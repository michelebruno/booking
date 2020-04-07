import React, { useState, useEffect } from "react"

import PropTypes from 'prop-types'


import { isInvalid } from "../_services/formValidation";
import Button from '@material-ui/core/Button';
import TextField from '@material-ui/core/TextField';
import InputAdornment from '@material-ui/core/InputAdornment';
import MenuItem from '@material-ui/core/MenuItem';
import Grid from '@material-ui/core/Grid';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Switch from '@material-ui/core/Switch';

const ProdottiForm = ({ url, onSuccess, spacing, xs, sm, md, lg, xl }) => {

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


    const dynamicProps = field => {

        let props = {
            value: data[field],
            onChange: e => setData({ ...data, [field]: e.target.value }),
            variant: "outlined",
        }

        if (sending || created) {
            props.readOnly = true
        } else if (error && errors) {

            props.helperText = errors[field]
            props.error = isInvalid(errors, field)
        }

        return props
    }

    useEffect(() => {

        if (!sending) return;

        const source = axios.CancelToken.source()

        axios({
            method: 'POST',
            url: url,
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

    const gridItemsProps = {
        xs,
        sm,
        md,
        lg,
        xl,
    }

    return <form onSubmit={handleSubmit} className={"form-nuovo-prodotto "} >

        <Grid container alignItems="center" spacing={spacing}  >

            {error && api && api.message && <Grid item {...gridItemsProps} >
                {api.message}
            </Grid>}
            {created && api ? <Grid item xs={12} container >
                <div>Prodotto creato con codice {api.codice}.</div>
            </Grid> : <Grid item container spacing={5} >
                    <Grid item xs={12} md={6}  >
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
                    </Grid>
                    <Grid item>
                        {!customCod &&
                            <TextField
                                label="Codice"
                                required
                                {...dynamicProps("codice")}
                            />
                        }

                    </Grid>
                </Grid>}
            <Grid item xs={12} className="p-0" ></Grid>
            <Grid item {...gridItemsProps}  >
                <TextField
                    label="Titolo"
                    required
                    {...dynamicProps("titolo")}
                />
            </Grid>
            <Grid item {...gridItemsProps} >
                <TextField
                    label="Descrizione"
                    multiline
                    fullWidth
                    {...dynamicProps("descrizione")}

                />
            </Grid>

            <Grid item {...gridItemsProps} >
                <TextField
                    label="Stato"
                    select
                    required
                    SelectProps={{
                        style: {
                            minWidth: "8em",
                        },
                    }}
                    {...dynamicProps("stato")}
                >
                    {stati.map(stato => <MenuItem value={stato.value} key={stato.value} >
                        {stato.label}
                    </MenuItem>)}
                </TextField>
            </Grid>
            <Grid item {...gridItemsProps} >
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
                    {...dynamicProps("iva")}
                />
            </Grid>
            <Grid item {...gridItemsProps} >
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
                    {...dynamicProps("disponibili")}
                />
            </Grid>

            <Grid item {...gridItemsProps} >
                <Button variant="contained" type="submit" color="primary" size="large" disabled={sending || created} >Invia</Button>
            </Grid>
        </Grid>
    </form >
}

ProdottiForm.defaultProps = {
    xs: 12,
    onSuccess: () => undefined,
    spacing: 5,
}

ProdottiForm.propTypes = {
    url: PropTypes.string,
    onSuccess: PropTypes.func,
    xs: PropTypes.number,
    sm: PropTypes.number,
    md: PropTypes.number,
    lg: PropTypes.number,
    xl: PropTypes.number,
}

export default ProdottiForm;