import { useState , useEffect } from 'react'
import localization from './localization';

export function serverSideOptions( onChangeFilter , columns , customData ) {

    let labels = localization.it.MUIDatatableLabels

    if ( customData && customData.errorMessage ) {
        labels.body.noMatch = customData.errorMessage
    }
    return {

        serverSide : true,
    
        onChangePage : page => {
            onChangeFilter( { page : page + 1 } );
        },
    
        onChangeRowsPerPage : per_page => onChangeFilter( { per_page : per_page , page : 1 } ),
    
        onFilterChange : ( changedCol , filterList , context ) => {
            let index = columns.findIndex( ( c ) => c.name === changedCol );
            let nome = columns[index]._filterName || columns[index].name
            onChangeFilter({ [nome] : filterList[index] })
        },
    
        elevation : 0, // il box-shadow
    
        textLabels : { ... labels },

    }

}

/**
 * 
 * @param {string} baseUrl L'url da cui prendere la collezione
 * @param {object} defaultFilter 
 * @returns { Array[ collection , filter,  setFilter , datatableOptions , reload ]}
 */
export default function useServerSideCollection( baseUrl , defaultFilter ) {
    
    const [collection, setCollection] = useState()
    const [filter, _setFilter] = useState(defaultFilter)


    const setFilter = ( addFilter ) => {
        let n = Object.assign( {}, filter, addFilter )
        _setFilter(n);
    }

    const loadApi = () => {
    
        const source = axios.CancelToken.source() 

        let url = baseUrl 

        let query = []

        for ( let d in filter ) {
            query.push( encodeURIComponent(d) + "=" + encodeURIComponent( filter[d] ) )
        }

        if ( query.length ) {
            url += "?" + query.join("&");
        }

        axios.get( url , { cancelToken : source.token } )
            .then( response => setCollection( response.data ) )
            .catch( e => {
                if ( axios.isCancel(e) ) return;
            })

        return source.cancel
    }

    useEffect(() => {
        
        return loadApi()

    }, [ filter ])

    const datatableOptions = ( ...args ) => {
        return collection && {
            page : collection.current_page - 1,
            count : collection.total,
            elevation : 0,
            ...serverSideOptions( setFilter , ...args ),
        }
    }

    const reload = () => _setFilter( f => f)

    return [ collection , filter,  setFilter , datatableOptions , reload ]

}