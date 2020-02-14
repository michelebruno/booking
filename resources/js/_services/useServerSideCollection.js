import { useState , useEffect } from 'react'
import localization from './localization';

/**
 * 
 * @param {string} baseUrl L'url da cui prendere la collezione
 * @param {object} defaultFilter 
 * @returns { Array[ collection , filter,  setFilter , datatableOptions , reload ]}
 */
export default function useServerSideCollection( baseUrl , defaultFilter ) {
    
    const [collection, setCollection] = useState()

    const [filter, _setFilter] = useState(defaultFilter)

    const getSordDirectionByName = n => {
        if ( filter && filter.orderBy && filter.orderBy === n) {
            return filter.order || "none"
        }
    }

    const setFilter = ( addFilter ) => {

        if (typeof addFilter === "function") {
            _setFilter(addFilter)
            return;
        } else if ( typeof addFilter === "boolean" && ! addFilter ) {
            _setFilter( {} ) 
            return
        }

        _setFilter( prevFilter => {

            const nextFilter = Object.assign( {}, prevFilter)

            Object.entries(addFilter).map( ( [ key , value ] ) =>{
                if ( Array.isArray(value) ) { // Bisogna impostare la proprietà
                    nextFilter[key] = value
                } else if ( value ) {
                    nextFilter[key] = [ value ]
                } else {
                    if ( _.has( nextFilter , key ) ) {
                        _.unset( nextFilter , key )
                    }
                }
            } )            

            return nextFilter
        });
    }

    const makeURLQuery = tempFilter => {

        let url = baseUrl 

        let query = []

        let f = tempFilter ? Object.assign({}, filter, tempFilter) : filter

        for ( let d in f ) {
            query.push( encodeURIComponent( _.snakeCase(d) ) + "=" + encodeURIComponent( f[d] ) )
        }

        if ( query.length ) {
            url += "?" + query.join("&");
        }

        return url
    }

    const loadApi = ( tempFilter ) => {
    
        const source = axios.CancelToken.source() 

        const url = makeURLQuery(tempFilter)

        axios.get( url , { cancelToken : source.token } )
            .then( response => setCollection( response.data ) )
            .catch( e => {

                if ( axios.isCancel(e) ) return;

                if (e.response) {
                    setCollection( () => ({ data : [] , error : e.response.data.message }) )
                } else console.log(e);
                
            })

        return source.cancel
    }

    useEffect( () => {
        return loadApi()
    }, [ filter ] )    

    const datatableOptions = ( columns , customData ) => {

        const cNames =  columns.map(c => c.name )
        
        const cLabels =  columns.map(c => c.label ) 

        let labels = localization.it.MUIDatatableLabels

        if ( customData && customData.errorMessage ) {
            labels.body.noMatch = customData.errorMessage
        }
        
        return {

            serverSide : true,

            onChangePage : page => _setFilter( filter => Object.assign( {}, filter , { page : page + 1 } ) ),

            onChangeRowsPerPage : per_page => _setFilter( ( p ) => Object.assign( {}, p, { per_page , page : 1 } ) ),

            onFilterChange : ( changedCol , filterList , context ) => {

                if ( context == "reset" ) {

                    setFilter( prev => prev.per_page ? { per_page : prev.per_page } : {} )

                } else if ( context === "chip" ) {

                    setFilter( prev => _.omit(prev, changedCol) )

                } else {

                    const index = cNames.findIndex( ( c ) => c === changedCol )

                    if ( typeof cNames[index] !== "undefined" ) {

                        let nome = cNames[index]

                        setFilter({ [nome] : filterList[index] })

                    }

                }

            },

            onColumnSortChange : ( changedCol , direction ) => _setFilter( prev => Object.assign( {} , prev, { order : (direction === "descending") ? "desc" : "asc" , orderBy :changedCol } ) ) ,

            serverSideFilterList : cNames ? cNames.map( (name , index ) => {
                let a = _.get(filter, name)
                return a ? [ cLabels[index] + ": " + a ] : []
            } ) : undefined ,

            elevation : 0, // il box-shadow

            print : false,

            download : false,

            page : collection.current_page - 1,

            count : collection.total,

            textLabels : { ... labels },
        }
    }

    const reload = () => _setFilter( filter )

    return [ collection , datatableOptions , { filter , setFilter , reload , setCollection , getSordDirectionByName } ]

}