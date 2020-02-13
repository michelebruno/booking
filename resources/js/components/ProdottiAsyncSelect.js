import React , { useState , useEffect } from 'react'

import Autocomplete from '@material-ui/lab/Autocomplete';
import TextField from '@material-ui/core/TextField'
import CircularProgress from '@material-ui/core/CircularProgress';

const ProdottiAsyncSelect = ( { url , label  } ) => {

    const [options, setOptions] = useState([])

    const [loading, setLoading] = useState(true)
    
    const [open, setOpen] = useState(false)

    const [input, setInput] = useState("")
    

    useEffect( () => {

        if ( ! url ) {
            console.log("No URL")
            return;
        }


        let surl = url ;

        if ( input ) {
            surl += "?s=" + input
        }

        const source = axios.CancelToken.source()

        setLoading(true)

        axios.get( surl , { cancelToken : source.token })
            .then( ( res ) => {
                setOptions(res.data)
                setLoading(false)
            })
            .catch( e => {
                if ( axios.isCancel(e) ) {
                    return;
                }
                setOptions([])
            })
    
        return source.cancel

    }, [ input ]);
    return <>
        <Autocomplete
            options={options} 
            open={open}
            value={input}
            onInputChange={ e => setInput( e.target.value ) }
            onOpen={ () => setOpen(true) }
            onClose={ () => setOpen(false) }
            loading={loading} 
            getOptionLabel={ option =>{
                if ( option && option.titolo ) {
                    return option.titolo 
                }
            }}
            renderInput={ params => {
                return <TextField 
                    { ... params }
                    label={ label }
                    fullWidth
                    variant="outlined"
                    InputProps={{
                    ...params.InputProps,
                    endAdornment: (
                        <React.Fragment>
                        {loading ? <CircularProgress color="inherit" size={20} /> : null}
                        {params.InputProps.endAdornment}
                        </React.Fragment>
                    ),
                    }}
                    />
                }
            } 
            />
    </>
}

ProdottiAsyncSelect.defaultProps = {
    label : "Seleziona un prodotto."
}

export default ProdottiAsyncSelect